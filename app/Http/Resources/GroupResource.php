<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroupResource extends JsonResource
{
    public function toArray($request)
    {
       

        $baseUrl = rtrim(config('api_url.baseUrl_1'), '/');

        // ✅ Fetch branch and shift data from external API
        $branchData = $this->branch_id ? $this->fetchBranchDetails($baseUrl, $this->branch_id) : null;
        $shiftData  = $this->shift_id ? $this->fetchShiftDetails($baseUrl, $this->shift_id) : null;

       

        // ✅ Map through all related employees
        $employees = $this->employees->map(function ($employee) use ($baseUrl) {
            $personType = $employee->person_type;
            $profileId = $employee->profile_id;

            // Fetch details from external API
            $employeeData = $this->fetchEmployeeDetails($baseUrl, $personType, $profileId);

            return [
                'id' => $employee->id,
                'profile_id' => $profileId,
                'person_type' => $personType,
                'name' => $employeeData['name'] ?? null,
                'mobile_number' => $employeeData['mobile_number'] ?? null,
                'present_address' => $employeeData['present_address'] ?? null,
                'picture' => $employeeData['picture'] ?? null,
                'division' => $employeeData['division'] ?? null,
                'district' => $employeeData['district'] ?? null,
                'upazila' => $employeeData['upazila'] ?? null,
                'extra_data' => $employeeData ?? null,
            ];
        });

        // ✅ Final API response format
        return [
            'id' => $this->id,
            'group_name' => $this->group_name,
            'description' => $this->description,
            'status' => $this->status,
            'flexible_in_time' => $this->flexible_in_time,
            'flexible_out_time' => $this->flexible_out_time,

            // ✅ External API branch info
            'branch' => $branchData ? [
                'id' => $branchData['id'] ?? null,
                'branch_code' => $branchData['branch_code'] ?? null,
                'branch_name_en' => $branchData['branch_name_en'] ?? null,
                'branch_name_bn' => $branchData['branch_name_bn'] ?? null,
            ] : null,

            // ✅ External API shift info
            'shift' => $shiftData ? [
                'id' => $shiftData['id'] ?? null,
                'shift_name_en' => $shiftData['shift_name_en'] ?? null,
                'shift_name_bn' => $shiftData['shift_name_bn'] ?? null,
                'branch' => $shiftData['branch'] ?? null,
            ] : null,

            // ✅ Local work days
            'work_days' => $this->workDays->map(function ($workDay) {
                return [
                    'id' => $workDay->id,
                    'day_name' => $workDay->day_name,
                ];
            }),

            // ✅ Mapped employee details
            'employees' => $employees,

            'employee_count' => $this->employees->count(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    // 🔹 Fetch Employee Details
    private function fetchEmployeeDetails($baseUrl, $personType, $profileId)
    {
        Log::info('Fetching employee details', [
            'baseUrl' => $baseUrl,
            'personType' => $personType,
            'profileId' => $profileId,
        ]);

      $endpoint = match ($personType) {
    1 => 'teachers',
    2 => 'staffs',
    3 => 'students',
    default => null,
};

if ($endpoint) {
    $url = "{$baseUrl}/api/v3/{$endpoint}/{$profileId}"; // ✅ correct full URL
    try {
        $response = Http::timeout(5)->get($url);
        if ($response->successful()) {
            $extraData = $response->json('data') ?? [];
        } else {
            Log::warning('Employee API failed', [
                'url' => $url,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }
    } catch (\Throwable $e) {
        Log::error('Employee API exception', ['url' => $url, 'error' => $e->getMessage()]);
    }
}


        try {
            $response = Http::timeout(6)->get($url);
\Log::info('xx');
            if ($response->successful()) {
                $json = $response->json();
                return $json['data'] ?? $json;
            }

            Log::warning('Employee API failed', [
                'url' => $url,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Employee API exception', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);
        }

        return [];
    }

    // 🔹 Fetch Branch Details
    private function fetchBranchDetails($baseUrl, $branchId)
    {
        $url = "{$baseUrl}/branch-list";
        try {
            $response = Http::timeout(6)->get($url);
            if ($response->successful()) {
                $branches = $response->json()['data'] ?? [];
                return collect($branches)->firstWhere('id', $branchId);
            }
        } catch (\Throwable $e) {
            Log::error('Error fetching branch', ['url' => $url, 'error' => $e->getMessage()]);
        }

        return null;
    }

    // 🔹 Fetch Shift Details
    private function fetchShiftDetails($baseUrl, $shiftId)
    {
        $url = "{$baseUrl}/shift-list";
        try {
            $response = Http::timeout(6)->get($url);
            if ($response->successful()) {
                $shifts = $response->json()['data'] ?? [];
                return collect($shifts)->firstWhere('id', $shiftId);
            }
        } catch (\Throwable $e) {
            Log::error('Error fetching shift', ['url' => $url, 'error' => $e->getMessage()]);
        }

        return null;
    }
}
