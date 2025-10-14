<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

class ShiftRepository extends BaseRepository
{
    public function getAll(): Collection
    {
        $url = "{$this->baseUrl}/api/v3/shift-list";
        $data = $this->makeApiCall($url);
        
        return collect($data)->map(function ($shift) {
            return [
                'id' => $shift['uid'] ?? $shift['id'] ?? null,
                'shift_name_en' => $shift['shift_name_en'] ?? $shift['shift_name'] ?? null,
                'branch_id' => $shift['branch_id'] ?? null,
                'shift_start_time' => $shift['shift_start_time'] ?? null,
                'shift_end_time' => $shift['shift_end_time'] ?? null,
            ];
        })->filter();
    }

    public function getByBranch($branchId): Collection
    {
        return $this->getAll()->where('branch_id', $branchId)->values();
    }
}