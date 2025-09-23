<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
// use Illuminate\Http\Request;

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
            'employees' => $this->employees->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'profile_id' => $employee->profile_id,
                    'name' => $employee->name,
                    'joining_date' => $employee->joining_date,
                    'mobile_number' => $employee->mobile_number,
                    'present_address' => $employee->present_address,

                      'division' => $employee->division ? [
                        'id' => $employee->division->id,
                        'name' => $employee->division->division_name_en
                    ] : null,
                    'district' => $employee->district ? [
                        'id' => $employee->district->id,
                        'name' => $employee->district->district_name_en
                    ] : null,
                    'upazila' => $employee->upazila ? [
                        'id' => $employee->upazila->id,
                        'name' => $employee->upazila->upazila_name_en
                    ] : null,
                    // 'division_id' => $employee->division_id,
                    // 'district_id' => $employee->district_id,
                    // 'upazila_id' => $employee->upazila_id,
                    'company_id' => $employee->company_id,
                    'picture' => $employee->picture,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'employee_count' => $this->employees->count(),
        ];
    }
}