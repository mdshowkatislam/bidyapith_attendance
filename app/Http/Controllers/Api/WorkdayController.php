<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\WorkDay;
use Illuminate\Http\Request;

class WorkdayController extends Controller
{
    // List all work days
    public function index()
    {
        $workDays = WorkDay::all();
        if ($workDays->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Work days Not found.',
                'work_days' => $workDays,
            ], 404);
        }
        return response()->json([
            'message' => 'Work days fetched successfully.',
            'work_days' => WorkDay::all(),
        ], 200);
    }

    // Store a new work day
    public function store(Request $request)
    { 
        $validated = $request->validate([
            'day_name' => 'required|string|unique:work_days,day_name',
            'is_weekend' => 'required|boolean',
        ]);

        $workDay = WorkDay::create([
            'day_name' => ucfirst(strtolower($validated['day_name'])),
            'is_weekend' => $validated['is_weekend']
        ]);

        return response()->json([
            'message' => 'Work day added successfully.',
            'data' => $workDay,
        ], 201);
    }

    // Get a single work day for editing
    public function edit($id)
    { \Log::info('edit hitte');
        $workDay = WorkDay::findOrFail($id);

        return response()->json($workDay);
    }

    // Update a work day
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'day_name' => 'required|string|unique:work_days,day_name,' . $id,
            'is_weekend' => 'required|boolean',
        ]);

        $workDay = WorkDay::findOrFail($id);
        $workDay->update([
            'day_name' => ucfirst(strtolower($validated['day_name'])),
            'is_weekend' => $validated['is_weekend']
        ]);

        return response()->json([
            'message' => 'Work day updated successfully.',
            'data' => $workDay,
        ]);
    }

    // Delete a work day
    public function destroy($id)
    {
        $workDay = WorkDay::findOrFail($id);
        $workDay->delete();

        return response()->json([
            'message' => 'Work day deleted successfully.',
        ]);
    }
}
