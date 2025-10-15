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
                'shift_uid' => $shift['uid']  ?? null,
                'shift_name_en' => $shift['shift_name_en'] ?? $shift['shift_name'] ?? null,
                'branch_uid' => $shift['branch_id'] ?? null,
                'shift_start_time' => $shift['shift_start_time'] ?? null,
                'shift_end_time' => $shift['shift_end_time'] ?? null,
            ];
        })->filter();
    }

    public function getByBranch($branchUid): Collection
    {
        return $this->getAll()->where('branch_uid', $branchUid)->values();
    }

     public function getShiftDetails($shiftUid)
    {
        $url = "{$this->baseUrl}/api/v3/shift/{$shiftUid}";
        \Log::info("Fetching shift from URL: {$url}");
        return $this->makeApiCall($url);
    }

    // public function getShiftsByBranch($branchUid)
    // {
    //     $url = "{$this->baseUrl}/api/v3/branch/{$branchUid}/shifts";
    //     return $this->makeApiCall($url);
    // }
}