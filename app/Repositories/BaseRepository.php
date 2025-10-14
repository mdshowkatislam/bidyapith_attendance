<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

abstract class BaseRepository
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('api_url.baseUrl_1'), '/');
    }

    protected function makeApiCall($url, $timeout = 10)
    {
        try {
            $response = Http::timeout($timeout)->get($url);
            if ($response->successful()) {
                return $response->json('data') ?? [];
            }
            Log::warning("API call failed", ['url' => $url, 'status' => $response->status()]);
        } catch (\Throwable $e) {
            Log::error("API call exception", ['url' => $url, 'error' => $e->getMessage()]);
        }
        return [];
    }
}