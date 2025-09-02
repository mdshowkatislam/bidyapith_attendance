<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\DbLocationSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DatabaseManageController extends Controller
{
    public function updateTimeSchedule(Request $request)
    { 
        $validated = $request->validate([
            'location' => 'nullable|string',
            'syncTimeName' => 'required|in:1,2,3,4,5,6,7',
        ]);

        $dbdata = [
            'location' => $validated['location'],
            'syncTimeName' => $validated['syncTimeName'],
        ];

        try {
           
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'accept' => 'application/json',
            ])
                ->withOptions(['verify' => false])
                ->post(config('api_url.endpoint'), ['dbdata' => $dbdata]);

            Log::info('API Response:', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            // Only proceed if API call is successful (status 200 or 2xx)
            if ($response->successful()) {
                DB::beginTransaction();

                DbLocationSetting::updateOrCreate(
                    ['key' => 'sync_time'],
                    [
                        'value' => $validated['syncTimeName'],
                        'db_location' => $validated['location'] ?? null,
                    ]
                );

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Schedule and DB location updated!',
                ], 200);
            } else {
                throw new \Exception('API call failed with status: ' . $response->status());
            }
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Update Time Schedule Error:', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update schedule. API or DB operation failed.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
