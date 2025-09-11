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
            'work_days' => $this->workDays->map(function ($workDay) {
                return [
                    'id' => $workDay->id,
                    'day_name' => $workDay->day_name
                ];
            }),
            'employees' => $this->employees->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'name' => $employee->name
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'employee_count' => $this->employees->count(),
        ];
    }
}