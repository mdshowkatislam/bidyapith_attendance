<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HolidayController extends Controller
{
    // GET all holidays
    public function index()
    {
        return response()->json(Holiday::all(), 200);
    }

    // POST: Create new holiday
    public function store(Request $request)
    {
      
        $validator = Validator::make($request->all(), [
            'holiday_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string|max:1000',
            'status' => 'nullable|in:0,1',
        ]);

        // Check composite uniqueness manually (since Laravel doesn't validate composite unique out-of-the-box)
        $exists = Holiday::where('holiday_name', $request->holiday_name)
                         ->where('start_date', $request->start_date)
                         ->exists();

        if ($exists) {
            return response()->json(['error' => 'This holiday on the given start date already exists.'], 409);
        }

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $holiday = Holiday::create($validator->validated());

        return response()->json([
            'message' => 'Holiday created successfully.',
            'data' => $holiday
        ], 201);
    }

    // GET: Show a specific holiday
    public function show($id)
    {
        $holiday = Holiday::find($id);

        if (!$holiday) {
            return response()->json(['message' => 'Holiday not found.'], 404);
        }

        return response()->json($holiday);
    }

    // PUT: Update a specific holiday
    public function update(Request $request, $id)
    {
        $holiday = Holiday::find($id);

        if (!$holiday) {
            return response()->json(['message' => 'Holiday not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'holiday_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string|max:1000',
            'status' => 'nullable|in:0,1',
        ]);

        // Check composite uniqueness excluding current record
        $exists = Holiday::where('holiday_name', $request->holiday_name)
                         ->where('start_date', $request->start_date)
                         ->where('id', '!=', $id)
                         ->exists();

        if ($exists) {
            return response()->json(['error' => 'This holiday on the given start date already exists.'], 409);
        }

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $holiday->update($validator->validated());

        return response()->json([
            'message' => 'Holiday updated successfully.',
            'data' => $holiday
        ]);
    }

    // DELETE: Remove a holiday
    public function destroy($id)
    {
        $holiday = Holiday::find($id);

        if (!$holiday) {
            return response()->json(['message' => 'Holiday not found.'], 404);
        }

        $holiday->delete();

        return response()->json(['message' => 'Holiday deleted successfully.']);
    }
}
