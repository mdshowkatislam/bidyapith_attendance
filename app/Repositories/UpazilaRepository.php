<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

class UpazilaRepository extends BaseRepository
{
    public function getAll(): Collection
    {
        $url = "{$this->baseUrl}/api/v3/upazila";
        $data = $this->makeApiCall($url);
        
        return collect($data)->map(function ($upazila) {
            return [
                'id' => $upazila['id'] ?? $upazila['uid'] ?? null,
                'upazila_name_en' => $upazila['upazila_name_en'] ?? $upazila['upazila_name'] ?? null,
                'district_id' => $upazila['district_id'] ?? null,
            ];
        })->filter();
    }

    public function getByDistrict($districtId): Collection
    {
        return $this->getAll()->where('district_id', $districtId)->values();
    }
     public function getUpazilaDetails($upazilaId)
    {
        $url = "{$this->baseUrl}/api/v3/upazila/{$upazilaId}";
        return $this->makeApiCall($url);
    }

    // public function getUpazilasByDistrict($districtId)
    // {
    //     $url = "{$this->baseUrl}/api/v3/district/{$districtId}/upazilas";
    //     return $this->makeApiCall($url);
    // }
}