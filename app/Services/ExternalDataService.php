<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class ExternalDataService
{
    public static function fetchBranchDetails($baseUrl, $branchUid)
    {
        $response = Http::get("$baseUrl/api/v3/branch/$branchUid");
        return $response->ok() ? $response->json() : null;
    }

    public static function fetchShiftDetails($baseUrl, $shiftUid)
    {
        $response = Http::get("$baseUrl/api/v3/shift/$shiftUid");
        return $response->ok() ? $response->json() : null;
    }
}
