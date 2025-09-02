<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // \Log::info($this->request);
        return [
            'id' => $this->id,
            'group_name' => $this->group_name,
            'description' => $this->description,
            'flexible_in_time' => $this->flexible_in_time,
            'flexible_out_time' => $this->flexible_out_time,
            'status' => $this->status ? 'Active' : 'Inactive',
            // Only shift name
            'shift_name' => optional($this->shift)->shift_name ?? 'N/A',
            // Only day names
            'work_days' => $this->workDays->pluck('day_name'),
            // Only count of employees
            'employee_count' => $this->employees->count(),
        ];
    }
}
