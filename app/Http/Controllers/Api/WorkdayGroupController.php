<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\WorkDay;
use Illuminate\Http\Request;

class WorkdayGroupController extends Controller
{
    public function index(Group $group)
    {
        if (!$group) {
            return response()->json([
                'message' => 'Group not found.'
            ], 404);
        }

        return response()->json([
            'group' => $group->group_name,
            'work_day' => $group->workDays->isNotEmpty() ? $group->workDays : 'No work days assigned.',
        ]);
    }

    // Assign employees to a group (accepts array of employee IDs)
    public function store(Request $request, Group $group)
    {
        // dd($group);
        $validated = $request->validate([
            'work_day_ids' => 'required|array',
            'work_day_ids.*' => 'exists:work_days,id',
        ]);

        $group->workDays()->syncWithoutDetaching($validated['work_day_ids']);  // prevent duplicate entries

        return response()->json([
            'message' => 'Group wise working day assigned successfully.',
            'assigned_days' => $group->workDays,
        ]);
    }

    public function update(Request $request, Group $group)
    {
        $validated = $request->validate([
            'work_day_ids' => 'required|array',
            'work_day_ids.*' => 'exists:work_days,id',
        ]);

        // Replace existing group–work_day relationships with new ones
        $group->workDays()->sync($validated['work_day_ids']);

        return response()->json([
            'message' => 'Group-wise working days updated successfully.',
            'assigned_days' => $group->workDays,
        ]);
    }

    // Remove a single employee from a group
    public function destroy(Group $group, WorkDay $work_day)
    {
        $group->workDays()->detach($work_day->id);

        return response()->json([
            'message' => 'Working day removed from group.',
        ]);
    }

    public function detachAll(Group $group)
    {
        try {
            // Detach returns the number of rows removed from the pivot table
            $detached = $group->workDays()->detach();

            if ($detached > 0) {
                return response()->json([
                    'message' => 'All working days have been removed from the group.',
                    'removed_count' => $detached
                ], 200);
            } else {
                return response()->json([
                    'message' => 'No working days were assigned to this group.',
                ], 200);
            }
        } catch (\Exception $e) {
            \Log::error('Error detaching work days from group ID ' . $group->id . ': ' . $e->getMessage());

            return response()->json([
                'error' => 'An error occurred while removing work days from the group.'
            ], 500);
        }
    }
}
