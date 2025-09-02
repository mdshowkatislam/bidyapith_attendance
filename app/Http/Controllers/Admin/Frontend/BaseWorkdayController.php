<?php

namespace App\Http\Controllers\Admin\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BaseWorkdayController extends Controller
{
    public function index()
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'accept' => 'application/json',
        ])
        ->withOptions(['verify' => false])
        ->get('http://attendance2.localhost.com/api/day_manage/list');

        $work_days = $response->json()['work_days'] ?? [];
        
        return view('admin.frontend.work_day.index', compact('work_days'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'day_name' => 'required|string|unique:work_days,day_name',
            'is_weekend' => 'required|boolean',
        ]);

        Http::withOptions(['verify' => false])
            ->post('http://attendance2.localhost.com/api/day_manage/store', $validated);

        return redirect()->route('work_day.create')->with('success', 'Work day created successfully.');
    }

    public function edit($id)
    {
        $response = Http::withOptions(['verify' => false])
            ->get("http://attendance2.localhost.com/api/day_manage/edit/{$id}");

        $work_day = $response->json()['work_day'] ?? null;

        if (!$work_day) {
            return redirect()->route('work_day.create')->withErrors('Work day not found.');
        }
        // dd($work_day);

       return response()->json($work_day);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'day_name' => "required|string|unique:work_days,day_name,{$id}",
            'is_weekend' => 'required|boolean',
        ]);

        Http::withOptions(['verify' => false])
            ->put("http://attendance2.localhost.com/api/day_manage/update/{$id}", $validated);

        return redirect()->route('work_day.create')->with('success', 'Work day updated successfully.');
    }

    public function destroy($id)
    {
        Http::withOptions(['verify' => false])
            ->delete("http://attendance2.localhost.com/api/day_manage/delete/{$id}");

        return redirect()->route('work_day.create')->with('success', 'Work day deleted successfully.');
    }
}
