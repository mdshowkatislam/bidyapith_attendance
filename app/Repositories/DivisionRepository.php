<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

class DivisionRepository extends BaseRepository
{
    public function getAll(): Collection
    {
        $url = "{$this->baseUrl}/api/v3/division";
        $data = $this->makeApiCall($url);
        
        return collect($data)->map(function ($division) {
            return [
                'id' => $division['id'] ?? $division['uid'] ?? null,
                'division_name_en' => $division['division_name_en'] ?? $division['division_name'] ?? null,
            ];
        })->filter();
    }
      public function getDivisionDetails($divisionId)
    {
        $url = "{$this->baseUrl}/api/v3/division/{$divisionId}";
        return $this->makeApiCall($url);
    }
}