<?php

namespace App\Http\Controllers\Admin\Frontend;

// use App\Http\Controllers\Admin\Frontend\Http;
use App\Http\Controllers\Controller;
use App\Models\ShiftSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BaseShiftController extends Controller
{
    public function index()
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
                ->timeout(30)
                ->withOptions(['verify' => false])
                ->get('http://attendance2.localhost.com/api/shift_manage/list');

            if (!$response->successful()) {
                \Log::error('API Request Failed', ['status' => $response->status(), 'body' => $response->body()]);
                throw new \Exception('API request failed with status: ' . $response->status());
            }

            $responseData = $response->json();
        
            // Handle different response scenarios
            $shifts = $responseData['message'] === 'Shift not found.'
                ? []
                : ($responseData['shifts'] ?? []);

            return view('admin.frontend.shift.index', compact('shifts'));
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // Handle connection errors
            \Log::error('API Connection Error: ' . $e->getMessage());
            return view('admin.frontend.shift.index', ['shifts' => []])
                ->with('error', 'Unable to connect to the API service.');
        } catch (\Exception $e) {
            // Handle other errors
            \Log::error('Shift Index Error: ' . $e->getMessage());
            return view('admin.frontend.shift.index', ['shifts' => []])
                ->with('error', 'An error occurred while fetching shifts.');
        }
    }

    public function create()
    {
        $response = Http::post(url('http://attendance2.localhost.com/api/shift_manage/add'));
        $branch = $response->json()['branch'];
        // dd($branch);
        return view('admin.frontend.shift.create', compact('branch'));
    }

    public function store(Request $request)
    {
        // \Log::info('Shift Store Request:', $request->all());
        Http::post(url('http://attendance2.localhost.com/api/shift_manage/store'), $request->all());
        return redirect()->route('shift.index');
    }

    public function edit($uid)
    {
        $response = Http::get(url("http://attendance2.localhost.com/api/shift_manage/edit/{$uid}"));
        $data = $response->json()['data'];
        return view('admin.frontend.shift.edit', compact('data'));
    }

    public function update(Request $request, $uid)
    {
        // \Log::info('Shift Update Request:', $request->all());
        Http::put(url("http://attendance2.localhost.com/api/shift_manage/update/{$uid}"), $request->all());
        return redirect()->route('shift.index');
    }

    public function destroy($uid)
    {
        Http::delete(url("http://attendance2.localhost.com/api/shift_manage/delete/{$uid}"));
        return redirect()->route('shift.index');
    }
}
