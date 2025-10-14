<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

class DistrictRepository extends BaseRepository
{
    public function getAll(): Collection
    {
        $url = "{$this->baseUrl}/api/v3/district";
        $data = $this->makeApiCall($url);
        
        return collect($data)->map(function ($district) {
            return [
                'id' => $district['id'] ?? $district['uid'] ?? null,
                'district_name_en' => $district['district_name_en'] ?? $district['district_name'] ?? null,
                'division_id' => $district['division_id'] ?? null,
            ];
        })->filter();
    }

    public function getByDivision($divisionId): Collection
    {
        return $this->getAll()->where('division_id', $divisionId)->values();
    }
}