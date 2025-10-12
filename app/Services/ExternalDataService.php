<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class ExternalDataService
{
    public static function fetchBranchDetails($baseUrl, $branchId)
    {
        $response = Http::get("$baseUrl/branches/$branchId");
        return $response->ok() ? $response->json() : null;
    }

    public static function fetchShiftDetails($baseUrl, $shiftId)
    {
        $response = Http::get("$baseUrl/shifts/$shiftId");
        return $response->ok() ? $response->json() : null;
    }
}
