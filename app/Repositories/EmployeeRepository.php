<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class EmployeeRepository extends BaseRepository
{
    /**
     * Fetch single employee details by type/profile ID.
     */
    public function getEmployeeDetails($personType, $profileId)
    {
        $endpoint = match ($personType) {
            1 => 'teacherAsEmp',
            2 => 'staffAsEmp',
            3 => 'studentAsEmp',
            default => null,
        };

        if (!$endpoint) {
            Log::warning("Invalid person type provided: {$personType}");
            return [
                'status' => false,
                'message' => 'Invalid person type.',
                'data' => [],
            ];
        }

        $url = "{$this->baseUrl}/api/v3/{$endpoint}/{$profileId}";
        // Log::info("Fetching employee details from URL: {$url}");

        try {
            $response = $this->makeApiCall($url);

            if (!is_array($response)) {
                return [
                    'status' => false,
                    'message' => 'Unexpected API response format.',
                    'data' => [],
                ];
            }

            if (isset($response['status']) && !$response['status']) {
                return [
                    'status' => false,
                    'message' => $response['message'] ?? 'API returned a failure response.',
                    'data' => $response['data'] ?? [],
                ];
            }

            return [
                'status' => true,
                'message' => 'Employee data fetched successfully.',
                'data' => $response['data'] ?? $response,
            ];
        } catch (Exception $e) {
            Log::error("Error fetching employee details from {$url}: " . $e->getMessage(), [
                'exception' => $e,
                'url' => $url,
                'person_type' => $personType,
                'profile_id' => $profileId,
            ]);

            return [
                'status' => false,
                'message' => 'Failed to fetch employee data. Please try again later.',
                'data' => [],
            ];
        }
    }

    /**
     * Fetch filtered employee list from external system
     * using location filters like division, district, upazila.
     */
    public function getFilteredEmployees(array $filters = [])
    {
        $filter_url = "{$this->baseUrl}/api/v3/filtered-employees";
        Log::info("Fetching filtered employees from URL: {$filter_url}", $filters);

        try {
            $response = Http::timeout(15)->post($filter_url, $filters);

            if ($response->failed()) {
                Log::warning("Filtered employee API failed", [
                    'url' => $filter_url,
                    'status' => $response->status(),
                ]);
                return [
                    'status' => false,
                    'message' => "Failed to fetch filtered employees.",
                    'data' => [],
                ];
            }

            $json = $response->json();
            if (!isset($json['data'])) {
                Log::warning("Filtered employee API returned unexpected format", ['response' => $json]);
                return [
                    'status' => false,
                    'message' => 'Unexpected API response.',
                    'data' => [],
                ];
            }

            return [
                'status' => true,
                'message' => 'Filtered employees fetched successfully.',
                'data' => $json['data'],
            ];
        } catch (Exception $e) {
            Log::error("Exception during filtered employee fetch: " . $e->getMessage(), [
                'url' => $filter_url,
                'filters' => $filters,
            ]);
            return [
                'status' => false,
                'message' => 'Exception occurred while fetching employees.',
                'data' => [],
            ];
        }
    }
}
