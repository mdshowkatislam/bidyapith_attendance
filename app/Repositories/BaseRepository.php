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
                // Log the full raw body for debugging
                // Log::info("Raw API response from {$url}:", [
                //     'body' => $response->body(),
                // ]);

                // Extract only 'data'
                $data = $response->json('data') ?? [];

                // If 'data' is empty, log a warning
                if (empty($data)) {
                    Log::warning("API responded successfully but 'data' is empty", [
                        'url' => $url,
                        'response_json' => $response->json(),
                    ]);
                }

                return $data;
            }

            // Log::warning('API call failed', [
            //     'url' => $url,
            //     'status' => $response->status(),
            //     'body' => $response->body(),
            // ]);
        } catch (\Throwable $e) {
            Log::error('API call exception', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);
        }

        return [];
    }
}
