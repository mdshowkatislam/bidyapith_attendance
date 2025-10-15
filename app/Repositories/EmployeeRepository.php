<?php

namespace App\Repositories;

class EmployeeRepository extends BaseRepository
{
    public function getEmployeeDetails($personType, $profileId)
    {
        $endpoint = match ($personType) {
            1 => 'teacherAsEmp',
            2 => 'staffAsEmp',
            3 => 'studentAsEmp',
            default => null,
        };

        if (!$endpoint) return [];

        $url = "{$this->baseUrl}/api/v3/{$endpoint}/{$profileId}";
        \Log::info("Fetching employee from URL: {$url}");
        return $this->makeApiCall($url);
    }
}