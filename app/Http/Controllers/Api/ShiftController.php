<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;
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
        $shifts = ShiftSetting::with('branch')->where('status', 1)->get();
        // \Log::info('Fetched Shifts:', $shift->toArray());   
        if (count($shifts) > 0) {
          
            return response()->json([
                'message' => 'Shifts fetched successfully.',
                'shifts' => $shifts,
            ], 200);
        }
        return response()->json([
            'message' => 'Shift not found.'
        ], 404);
    }

    public function add()
    {
        $branch = Branch::where('rec_status', 1)->select('id', 'uid', 'branch_code', 'branch_name_en', 'branch_name_bn')->get();

        if (count($branch) > 0) {
            return response()->json([
                'message' => 'Branches fetched successfully.',
                'branch' => $branch,
            ], 200);
        }
        return response()->json([
            'message' => 'Branch not found.'
        ], 404);
    }

    public function store(Request $request)
    {
        $request->merge([
            'branch_code' => (int) $request->branch_code,
            'eiin' => $request->eiin ? (int) $request->eiin : null,
        ]);

        // \Log::info($request->all());

        $validator = Validator::make($request->all(), [
            'branch_code' => ['required', 'integer', Rule::exists('branches', 'branch_code')],
            'shift_name_en' => [
                'required',
                'string',
                Rule::unique('shift_settings')->where(function ($query) use ($request) {
                    return $query->where('branch_code', $request->branch_code);
                })
            ],
            'shift_name_bn' => ['nullable', 'string'],
            'start_time' => ['required', 'date_format:h:i A'],
            'end_time' => ['required', 'date_format:h:i A'],
            'description' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            \Log::info('Validation failed', $validator->errors()->toArray());
            return response()->json([
                'status' => 'validation_error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        // dd('test');
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

        ShiftSetting::create($request->all());

        return response()->json([
            'message' => 'Shift saved successfully!'
        ], 201);
    }

    public function update($uid, Request $request)
    {
        // \Log::info('Update Request Data:', $request->all());
        $shiftSetting = ShiftSetting::where('uid', $uid)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'branch_code' => ['required', 'integer', Rule::exists('branches', 'branch_code')],
            'shift_name_en' => [
                'required',
                'string',
                Rule::unique('shift_settings')->where(function ($query) use ($request, $shiftSetting) {
                    return $query
                        ->where('branch_code', $request->branch_code)
                        ->where('uid', '!=', $shiftSetting->uid);  // Exclude current record
                })
            ],
            'shift_name_bn' => ['nullable', 'string'],
            'start_time' => ['required', 'date_format:h:i A'],
            'end_time' => ['required', 'date_format:h:i A'],
            'description' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'validation_error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        // Parse time formats (same as store function)
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

        // Update the shift setting
        $shiftSetting->update([
            'branch_code' => $validated['branch_code'],
            'shift_name_en' => $validated['shift_name_en'],
            'shift_name_bn' => $validated['shift_name_bn'],
            'start_time' => $start->format('H:i'),  // store in 24-hour format
            'end_time' => $end->format('H:i'),
            'description' => $validated['description'],
            'eiin' => $request->input('eiin'),
            'status' => $request->status ?? 1,
        ]);

        return response()->json([
            'message' => 'Shift updated successfully!'
        ], 200);
    }

    public function edit($uid)
    {
        $shift = ShiftSetting::with('branch')->where('uid', $uid)->firstOrFail();
        $branchs = Branch::select('id','branch_code', 'branch_name_en', 'branch_name_bn')->where('rec_status', 1)->get();
        $data = ['shift' => $shift, 'branches' => $branchs];

        return response()->json([
            'message' => 'Shift found.',
            'data' => $data
        ], 200);
    }

    public function destroy($uid)
    {
        $shift = ShiftSetting::where('uid', $uid)->firstOrFail();

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
