<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

class BranchRepository extends BaseRepository
{
    public function getAll(): Collection
    {
        $url = "{$this->baseUrl}/api/v3/branch-list";
        $data = $this->makeApiCall($url);
        \Log::info('tt');
        \Log::info($data);
        
        return collect($data)->map(function ($branch) {
            return [
                'id' => $branch['uid'] ?? $branch['id'] ?? null,
                'branch_name_en' => $branch['branch_name_en'] ?? $branch['branch_name'] ?? null,
            ];
        })->filter();
    }
}