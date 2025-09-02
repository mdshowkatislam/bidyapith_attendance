<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GroupSpecialWorkdayController extends Controller
{
    // Assign special workdays to a group
    public function assign(Request $request, Group $group)
    {
        $validator = Validator::make($request->all(), [
            'special_workingday_ids'   => 'required|array',
            'special_workingday_ids.*' => 'exists:special_working_days,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Sync special workdays with the group
        $group->specialWorkdays()->syncWithoutDetaching($request->special_workday_ids);

        return response()->json([
            'message' => 'Special workdays assigned to group successfully.',
            'data'    => $group->specialWorkdays
        ]);
    }
    public function update(Request $request, Group $group)
{
    $validator = Validator::make($request->all(), [
        'special_workingday_ids'   => 'required|array',
        'special_workingday_ids.*' => 'exists:special_working_days,id',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // Replace existing special working day assignments
    $group->specialWorkdays()->sync($request->special_workingday_ids);

    return response()->json([
        'message' => 'Special workdays updated for the group successfully.',
        'data'    => $group->specialWorkdays
    ]);
}


    // Remove specific special workday from group
    public function detach(Group $group, $special_workday_id)
    {
        if (!$group->specialWorkdays()->find($special_workday_id)) {
            return response()->json(['error' => 'Special workday not assigned to this group.'], 404);
        }

        $group->specialWorkdays()->detach($special_workday_id);

        return response()->json(['message' => 'Special workday detached from group.']);
    }

    // List all special workdays for a group
    public function list(Group $group)
    {
        return response()->json([
            'group' => $group->group_name,
            'special_workdays' => $group->specialWorkdays
        ]);
    }
}
