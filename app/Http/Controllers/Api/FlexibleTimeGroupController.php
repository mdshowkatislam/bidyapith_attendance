<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\FlexibleTimeGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FlexibleTimeGroupController extends Controller
{
    
 public function index(Request $request)
{
    $flextimegroup = FlexibleTimeGroup::all();

    if ($flextimegroup->isEmpty()) {
        return response()->json([
            'success' => true,
            'message' => 'No flexible time group found.',
        ], 200);
    }

    return response()->json([
        'success' => true,
        'message' => 'Flexible time group found.',
        'flexible_times' => $flextimegroup,
    ], 200);
}


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group_id' => 'required|exists:groups,id',
            'flexible_in_time' => 'required|integer',
            'flexible_out_time' => 'nullable|integer',
            'day_name' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'nullable|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (FlexibleTimeGroup::where('group_id', $request->group_id)->exists()) {
            return response()->json([
                'error' => 'This group already has a flexible time assigned.'
            ], 409);
        }

        $flex = FlexibleTimeGroup::create($validator->validated());

        return response()->json([
            'message' => 'Flexible time assigned successfully.',
            'data' => $flex
        ], 201);
    }

    public function update(Request $request, FlexibleTimeGroup $flexibleTimeGroup)
    {
        $validator = Validator::make($request->all(), [
            'flexible_in_time' => 'required|integer',
            'flexible_out_time' => 'nullable|integer',
            'day_name' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'nullable|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $flexibleTimeGroup->update($validator->validated());

        return response()->json([
            'message' => 'Flexible time updated successfully.',
            'data' => $flexibleTimeGroup
        ]);
    }

    public function edit(FlexibleTimeGroup $flexibleTimeGroup)
    {
        return response()->json([
            'message' => 'Flexible time found.',
            'flextimegroup' => $flexibleTimeGroup
        ], 200);
    }

    public function show(FlexibleTimeGroup $flexibleTimeGroup)
    {
        return response()->json([
            'data' => $flexibleTimeGroup
        ], 200);
    }

    public function destroy(FlexibleTimeGroup $flexibleTimeGroup)
    {
        $flexibleTimeGroup->delete();

        return response()->json([
            'message' => 'Flexible time deleted successfully!'
        ], 200);
    }
}
