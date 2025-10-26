<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

class BranchRepository extends BaseRepository
{
    public function getAll(): Collection
    {
        $url = "{$this->baseUrl}/api/v3/branch-list";
        $data = $this->makeApiCall($url);
     
        
        return collect($data)->map(function ($branch) {
            return [
                'branch_uid' => $branch['uid']  ?? null,
                'branch_name_en' => $branch['branch_name_en'] ?? $branch['branch_name'] ?? null,
            ];
        })->filter();
    }

     public function getBranchDetails($branchId)
    {
        $url = "{$this->baseUrl}/api/v3/branch/{$branchId}";
        // \Log::info("Fetching branch from URL: {$url}");
        return $this->makeApiCall($url);
    }

    public function getAllBranches()
    {
        $url = "{$this->baseUrl}/api/v3/branches";
        return $this->makeApiCall($url);
    }
}