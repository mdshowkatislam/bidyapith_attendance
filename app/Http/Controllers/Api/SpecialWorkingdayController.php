<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\SpecialWorkingday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SpecialWorkingdayController extends Controller
{
    public function index()
    { 
        $specialWorkdays = SpecialWorkingday::with('groups')->latest()->get();
        $total = $specialWorkdays->count();

        return response()->json([
            'status' => true,
            'message' => 'Special workdays fetched successfully.',
            'workdays' => $specialWorkdays,
            'total' => $total
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date|unique:special_working_days,date',
            'day_type' => 'nullable|integer|in:0,1',
            'description' => 'nullable|string',
            'group_ids' => 'required|array',
            'group_ids.*' => 'exists:groups,id',
            'status' => 'nullable|integer|in:0,1',  
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=>false,'message' => $validator->errors()], 422);
        }
        $data = $validator->validated();
        $workday = SpecialWorkingday::create([
            'date' => $data['date'],
            'day_type' => $data['day_type'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? null,
        ]);

        $workday->groups()->attach($data['group_ids']);

        return response()->json([
            'status'=>true,
            'message' => 'Special working day created successfully.',
            'data' => $workday->load('groups'),
        ]);
    }

    public function show(SpecialWorkingday $specialWorkingday)
    {
        return response()->json([
            'status'=>true,
            'data' => $specialWorkingday->load('groups')
        ]);
    }

    public function edit($id)
    {
        $specialWorkingday = SpecialWorkingday::with('groups')->findOrFail($id);
        $allGroups = Group::all();
        return response()->json([
            'message' => 'Special working day fetched successfully.',
            'data' => [
                'id' => $specialWorkingday->id,
                'date' => $specialWorkingday->date,
                'day_type' => $specialWorkingday->day_type,
                'description' => $specialWorkingday->description,
                'status' => $specialWorkingday->status,
                'group_ids' => $specialWorkingday->groups->pluck('id'),  // Only the IDs
                'groups' => $allGroups  // Full group objects if needed
            ]
        ]);
    }

    public function update(Request $request, SpecialWorkingday $specialWorkingday)
    {
        // \Log::info('Updating Special Working Day', [
        //     'id' => $specialWorkingday->id,
        //     'request_data' => $request->all()
        // ]);
        $validator = Validator::make($request->all(), [
            'date' => [
                'required',
                'date',
                Rule::unique('special_working_days', 'date')->ignore($specialWorkingday->id, 'id'),
            ],
            'day_type' => 'nullable|integer|in:0,1',
            'description' => 'nullable|string',
            'group_ids' => 'required|array',
            'group_ids.*' => 'exists:groups,id',
            'status' => 'nullable|integer|in:0,1',  
        ]);

        if ($validator->fails()) {
            return response()->json(['status'=>false,'message' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        $specialWorkingday->update([
            'date' => $data['date'],
            'day_type' => $data['day_type'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? null,
        ]);

        // Replace old group associations with new ones
        $specialWorkingday->groups()->sync($data['group_ids']);

        return response()->json([
            'status'=>true,
            'message' => 'Special working day updated successfully.',
            'data' => $specialWorkingday->load('groups'),
        ]);
    }

    public function destroy(SpecialWorkingday $specialWorkingday)
    {
       
        $specialWorkingday->groups()->detach();
        $specialWorkingday->delete();

        return response()->json([
            'status'=>true,
            'message' => 'Special workday deleted successfully.'
        ]);
    }
}
