<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Http;

class GroupResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'group_name' => $this->group_name,
            'description' => $this->description,
            'status' => $this->status,
            'flexible_in_time' => $this->flexible_in_time,
            'flexible_out_time' => $this->flexible_out_time,
            'shift' => $this->shift ? [
                'id' => $this->shift->id,
                'shift_name_en' => $this->shift->shift_name_en,
                'branch' => $this->shift->branch ? [
                    'branch_code' => $this->shift->branch->branch_code,
                    'branch_name_en' => $this->shift->branch->branch_name_en
                ] : null
            ] : null,

            'branch' => $this->branch ? [
                'id' => $this->branch->id,
                'branch_code' => $this->branch->branch_code,
                'branch_name_en' => $this->branch->branch_name_en,
                'branch_name_bn' => $this->branch->branch_name_bn,
            ] : null,

            'work_days' => $this->workDays->map(function ($workDay) {
                return [
                    'id' => $workDay->id,
                    'day_name' => $workDay->day_name
                ];
            }),

            // ✅ Employees  fetched dynamically
            'employees' => $this->employees->map(function ($employee) {
                $personType = $employee->person_type;
                $profileId = $employee->profile_id;

                $employeeData = $this->fetchEmployeeDetails($personType, $profileId);

                return [
                    'id' => $employee->id,
                    'profile_id' => $profileId,
                    'person_type' => $personType,
                    // Merge dynamic data from external service
                    'name' => $employeeData['name'] ?? null,
                    'mobile_number' => $employeeData['mobile_number'] ?? null,
                    'present_address' => $employeeData['present_address'] ?? null,
                    'picture' => $employeeData['picture'] ?? null,
                    'division' => $employeeData['division'] ?? null,
                    'district' => $employeeData['district'] ?? null,
                    'upazila' => $employeeData['upazila'] ?? null,
                    'extra_data' => $employeeData ?? null, // Optional: keep full response
                ];
            }),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'employee_count' => $this->employees->count(),
        ];
    }

    /**
     * Fetch employee details from external service based on person_type
     */
    private function fetchEmployeeDetails($personType, $profileId)
    {
        \Log::info('xxx');
        // External service base URL
        $baseUrl = 'http://local-master.bidyapith.com/api/v3';

        switch ($personType) {
            case 1: // Teacher
                $url = "{$baseUrl}/teachers/{$profileId}";
                break;

            case 2: // Staff
                $url = "{$baseUrl}/staff/{$profileId}";
                break;

            case 3: // Student
                $url = "{$baseUrl}/students/{$profileId}";
                break;

            default:
                return [];
        }

        try {
            $response = Http::timeout(5)->get($url);

            if ($response->successful()) {
                return $response->json()['data'] ?? [];
            } else {
                \Log::warning("Failed to fetch employee details", [
                    'url' => $url,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            }
        } catch (\Throwable $e) {
            \Log::error("Error fetching employee details: ".$e->getMessage());
        }

        return [];
    }
}
