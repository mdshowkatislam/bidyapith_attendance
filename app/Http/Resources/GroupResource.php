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
        $branchData = $this->branch_uid ? $this->fetchBranchDetails($baseUrl, $this->branch_uid) : null;
        $shiftData  = $this->shift_uid ? $this->fetchShiftDetails($baseUrl, $this->shift_uid) : null;

        Log::info('Branch Data:', [$branchData]);
        Log::info('Shift Data:', [$shiftData]);

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
                'name' => $employeeData['name_en'] ?? null,
                'designation' => $employeeData['designation'] ?? null,
                'mobile_number' => $employeeData['mobile_no'] ?? null,
                'present_address' => $employeeData['address'] ?? null,
                'picture' => $employeeData['image'] ?? null,
                'division' => $employeeData['division_id'] ?? null,
                'district' => $employeeData['district_id'] ?? null,
                'upazila' => $employeeData['upazilla_id'] ?? null, // spelling is same as DB
                // 'extra_data' => $employeeData ?? null,
            ];
        });

        return [
            'id' => $this->id,
            'group_name' => $this->group_name,
            'description' => $this->description,
            'status' => $this->status,
            'flexible_in_time' => $this->flexible_in_time,
            'flexible_out_time' => $this->flexible_out_time,

            // ✅ External API branch info
            'branch' => $branchData ? [
                'branch_uid' => $branchData['uid'] ?? null,
                'branch_name_en' => $branchData['branch_name_en'] ?? null,
                'branch_name_bn' => $branchData['branch_name'] ?? null, // here branch_name is okay ?
                'head_of_branch_id' => $branchData['head_of_branch_id'] ?? null,
            ] : null,

            // ✅ External API shift info
            'shift' => $shiftData ? [
                'shift_uid' => $shiftData['uid'] ?? null,
                'shift_name_en' => $shiftData['shift_name_en'] ?? null,
                'shift_name_bn' => $shiftData['shift_name_bn'] ?? null,
                'shift_start_time' => $shiftData['shift_start_time'] ?? null,
                'shift_end_time' => $shiftData['shift_end_time'] ?? null,
                'branch_uid' => $shiftData['branch_id'] ?? null,
            ] : null,

            // ✅ Local work days
            'work_days' => $this->workDays->map(function ($workDay) {
                return [
                    'id' => $workDay->id,
                    'day_name' => $workDay->day_name,
                ];
            }),

            'employees' => $employees,
            'employee_count' => $this->employees->count(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    // 🔹 Fetch Employee Details
    private function fetchEmployeeDetails($baseUrl, $personType, $profileId)
    {
        $endpoint = match ($personType) {
            1 => 'teacherAsEmp',
            2 => 'staffAsEmp',
            3 => 'studentAsEmp',
            default => null,
        };

        if (!$endpoint) return [];

        $url = "{$baseUrl}/api/v3/{$endpoint}/{$profileId}";
        Log::info("Fetching employee from URL: {$url}");

        try {
            $response = Http::timeout(6)->get($url);
            if ($response->successful()) {
                return $response->json('data') ?? [];
            }

            Log::warning('Employee API failed', [
                'url' => $url,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Employee API exception', ['url' => $url, 'error' => $e->getMessage()]);
        }

        return [];
    }

    // 🔹 Fetch Branch Details (returns single branch object)
    private function fetchBranchDetails($baseUrl, $branchUid)
    {
        $url = "{$baseUrl}/api/v3/branch/{$branchUid}";
        Log::info("Fetching branch from URL: {$url}");

        try {
            $response = Http::timeout(6)->get($url);
            if ($response->successful()) {
                $data = $response->json('data') ?? [];
                return is_array($data) ? $data : [];
            }
        } catch (\Throwable $e) {
            Log::error('Error fetching branch', ['url' => $url, 'error' => $e->getMessage()]);
        }

        return null;
    }

    // 🔹 Fetch Shift Details (returns single shift object)
    private function fetchShiftDetails($baseUrl, $shiftUid)
    {
        $url = "{$baseUrl}/api/v3/shift/{$shiftUid}";
        Log::info("Fetching shift from URL: {$url}");

        try {
            $response = Http::timeout(6)->get($url);
            if ($response->successful()) {
                $data = $response->json('data') ?? [];
                return is_array($data) ? $data : [];
            }
        } catch (\Throwable $e) {
            Log::error('Error fetching shift', ['url' => $url, 'error' => $e->getMessage()]);
        }

        return null;
    }
}
