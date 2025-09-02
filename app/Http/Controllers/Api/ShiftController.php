<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShiftSetting;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ShiftController extends Controller
{
    public function index()
    {
        $shift = ShiftSetting::where('status', 1)->get();

        if (count($shift) > 0) {
            return response()->json([
                'message' => 'Shifts fetched successfully.',
                'shift' => $shift,
            ], 200);
        }
        return response()->json([
            'message' => 'Shift not found.'
        ], 404);
    }

    public function store(Request $request)
    {
        // return response()->json($request->description);
        // dd('test');
        $validator = Validator::make($request->all(), [
            'shift_name' => ['required', 'unique:shift_settings,shift_name'],
            'start_time' => ['required', 'date_format:h:i A'],
            'end_time' => ['required', 'date_format:h:i A'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'validation_error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        // Parse using AM/PM format

        try {
            $start = Carbon::createFromFormat('h:i A', $validated['start_time']);
        } catch (\Exception $e) {
            try {
                $start = Carbon::createFromFormat('H:i', $validated['start_time']);
            } catch (\Exception $e) {
                return response()->json(['errors' => ['start_time' => 'Invalid start time format.']], 422);
            }
        }

        try {
            $end = Carbon::createFromFormat('h:i A', $validated['end_time']);
        } catch (\Exception $e) {
            try {
                $end = Carbon::createFromFormat('H:i', $validated['end_time']);
            } catch (\Exception $e) {
                return response()->json(['errors' => ['end_time' => 'Invalid end time format.']], 422);
            }
        }
        // If start and end time are equal
        if ($start->eq($end)) {
            return response()->json([
                'errors' => [
                    'start_time' => 'Start time and end time cannot be the same.',
                    'end_time' => 'Start time and end time cannot be the same.',
                ]
            ], 422);
        }

        // Handle overnight shifts
        if ($end->lt($start)) {
            $end->addDay();
        }

        ShiftSetting::create([
            'shift_name' => $validated['shift_name'],
            'start_time' => $start->format('H:i'),  // store in 24-hr format
            'end_time' => $end->format('H:i'),
            'description' => $request->input('description'),  // this line i am getting problem in vs code indecation ?
            'status' => $request->status ?? 1,
        ]);

        return response()->json([
            'message' => 'Shift saved successfully!'
        ], 201);
    }

    public function update(Request $request, ShiftSetting $shiftSetting)
    {
        $rules = [
            'start_time' => ['required', 'date_format:h:i A'],
            'end_time' => ['required', 'date_format:h:i A'],
        ];

        if ($request->filled('shift_name') && $request->input('shift_name') !== $shiftSetting->shift_name) {
            $rules['shift_name'] = [
                'required',
                Rule::unique('shift_settings', 'shift_name')->ignore($shiftSetting->id),
            ];
        } else {
            $rules['shift_name'] = ['required'];
        }

        $validated = $request->validate($rules);

        \Log::info('VALIDATED:', $validated);
        try {
            $start = Carbon::createFromFormat('h:i A', $validated['start_time']);
        } catch (\Exception $e) {
            try {
                $start = Carbon::createFromFormat('H:i', $validated['start_time']);
            } catch (\Exception $e) {
                return response()->json(['errors' => ['start_time' => 'Invalid start time format.']], 422);
            }
        }

        try {
            $end = Carbon::createFromFormat('h:i A', $validated['end_time']);
        } catch (\Exception $e) {
            try {
                $end = Carbon::createFromFormat('H:i', $validated['end_time']);
            } catch (\Exception $e) {
                return response()->json(['errors' => ['end_time' => 'Invalid end time format.']], 422);
            }
        }

        // Validate same time
        if ($start->eq($end)) {
            return response()->json([
                'errors' => [
                    'start_time' => 'Start time and end time cannot be the same.',
                    'end_time' => 'Start time and end time cannot be the same.',
                ]
            ], 422);
        }

        // Handle overnight shifts
        if ($end->lt($start)) {
            $end->addDay();
        }

        // Update shift record
        $shiftSetting->update([
            'shift_name' => $validated['shift_name'],
            'start_time' => $start->format('H:i'),  // store in 24-hour format
            'end_time' => $end->format('H:i'),
            'description' => $request->input('description'),
            'status' => $request->status ?? 1,
        ]);

        return response()->json([
            'message' => 'Shift updated successfully!!!'
        ], 200);
    }

    public function edit($id)
    {
        $shift = ShiftSetting::findOrFail($id);

        return response()->json([
            'message' => 'Shift found.',
            'shift' => $shift
        ], 200);
    }

    public function destroy($id)
    {
        $shift = ShiftSetting::find($id);

        if (!$shift) {
            return response()->json([
                'message' => 'Shift not found.'
            ], 404);
        }

        $shift->delete();

        return response()->json([
            'message' => 'Shift deleted successfully!'
        ], 200);
    }
}
