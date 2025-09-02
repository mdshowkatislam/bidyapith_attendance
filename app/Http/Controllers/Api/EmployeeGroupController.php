<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Group;
use Illuminate\Http\Request;

class EmployeeGroupController extends Controller
{
    public function index($groupId)
    {
        $group = Group::with('employees')->find($groupId);

        if (!$group) {
            return response()->json(['message' => 'Group not found.'], 404);
        }

        if ($group->employees->isEmpty()) {
            return response()->json([
                'group' => $group->group_name,
                'employees' => [],
                'message' => 'No employees found in this group.',
            ]);
        }

        return response()->json([
            'group' => $group->group_name,
            'employees' => $group->employees,
        ]);
    }

    public function store(Request $request, Group $group)
    {
        $validated = $request->validate([
            'employee_ids' => 'required|array',
            'employee_ids.*' => [
                'exists:employees,id',
                function ($attribute, $value, $fail) {
                    if (\DB::table('employee_group')->where('employee_id', $value)->exists()) {
                        $fail("Employee ID {$value} is already assigned to another group.");
                    }
                },
            ],
        ]);

        $group->employees()->attach($validated['employee_ids']);

        return response()->json([
            'message' => 'Employees assigned successfully.',
            'assigned_employees' => $group->employees,
        ]);
    }

    public function update(Request $request, Group $group)
{
    $validated = $request->validate([
        'employee_ids' => 'required|array',
        'employee_ids.*' => [
            'exists:employees,id',
            function ($attribute, $value, $fail) use ($group) {
                // Check if employee is already assigned to another group (not this one)
                $alreadyAssigned = \DB::table('employee_group')
                    ->where('employee_id', $value)
                    ->where('group_id', '!=', $group->id)
                    ->exists();

                if ($alreadyAssigned) {
                    $fail("Employee ID {$value} is already assigned to another group.");
                }
            },
        ],
    ]);

    // Replace old employee assignments with the new ones
    $group->employees()->sync($validated['employee_ids']);

    return response()->json([
        'message' => 'Employees updated successfully.',
        'assigned_employees' => $group->employees,
    ]);
}


    public function destroy(Group $group, Request $request)
    {
        $employeeIds = $request->input('employee_id');

        if (is_array($employeeIds) && count($employeeIds) > 0) {
            $group->employees()->detach($employeeIds);
        }

        return response()->json([
            'message' => 'Employee(s) removed from group.',
        ]);
    }

    public function detachAll(Group $group)
    {
        try {
            // Detach returns number of records deleted from the pivot table
            $detached = $group->employees()->detach();

            if ($detached > 0) {
                return response()->json([
                    'message' => 'All employees have been removed from the group.',
                    'removed_count' => $detached
                ], 200);
            } else {
                return response()->json([
                    'message' => 'No employees were assigned to this group.',
                ], 200);
            }
        } catch (\Exception $e) {
            \Log::error('Error detaching employees from group ID ' . $group->id . ': ' . $e->getMessage());

            return response()->json([
                'error' => 'An error occurred while removing employees from the group.'
            ], 500);
        }
    }
}
