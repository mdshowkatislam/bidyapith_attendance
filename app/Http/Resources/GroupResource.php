<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\ExternalDataService;
use Illuminate\Support\Facades\Log;

class GroupResource extends JsonResource
{
    public function toArray($request)
    {
        // Resolve the service inside the method
        $externalDataService = app(ExternalDataService::class);
        
        Log::info('Fetching data for Group ID: ' . $this->id);

        // Use service to fetch external data
        $branchData = $this->branch_uid ? $externalDataService->fetchBranchDetails($this->branch_uid) : null;
        $shiftData  = $this->shift_uid ? $externalDataService->fetchShiftDetails($this->shift_uid) : null;

        $employees = $this->employees->map(function ($employee) use ($externalDataService) {
            $employeeData = $externalDataService->fetchEmployeeDetails(
                $employee->person_type, 
                $employee->profile_id
            );

            return [
                'id' => $employee->id,
                'profile_id' => $employee->profile_id,
                'person_type' => $employee->person_type,
                'name' => $employeeData['name_en'] ?? null,
                'designation' => $employeeData['designation'] ?? null,
                'mobile_number' => $employeeData['mobile_no'] ?? null,
                'present_address' => $employeeData['address'] ?? null,
                'picture' => $employeeData['image'] ?? null,
                'division' => isset($employeeData['division_id']) ? 
                    $externalDataService->fetchDivisionName($employeeData['division_id']) : null,
                'district' => isset($employeeData['district_id']) ? 
                    $externalDataService->fetchDistrictName($employeeData['district_id']) : null,
                'upazila' => isset($employeeData['upazilla_id']) ? 
                    $externalDataService->fetchUpazilaName($employeeData['upazilla_id']) : null,
            ];
        });

        return [
            'id' => $this->id,
            'group_name' => $this->group_name,
            'description' => $this->description,
            'status' => $this->status,
            'flexible_in_time' => $this->flexible_in_time,
            'flexible_out_time' => $this->flexible_out_time,
            'branch' => $branchData ? [
                'branch_uid' => $branchData['uid'] ?? null,
                'branch_name_en' => $branchData['branch_name_en'] ?? null,
                'branch_name_bn' => $branchData['branch_name'] ?? null,
                'head_of_branch_id' => $branchData['head_of_branch_id'] ?? null,
            ] : null,
            'shift' => $shiftData ? [
                'shift_uid' => $shiftData['uid'] ?? null,
                'shift_name_en' => $shiftData['shift_name_en'] ?? null,
                'shift_name_bn' => $shiftData['shift_name_bn'] ?? null,
                'shift_start_time' => $shiftData['shift_start_time'] ?? null,
                'shift_end_time' => $shiftData['shift_end_time'] ?? null,
                'branch_uid' => $shiftData['branch_id'] ?? null,
            ] : null,
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
}