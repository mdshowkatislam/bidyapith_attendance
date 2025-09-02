<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GroupResource;
use App\Models\Employee;
use App\Models\Group;
use App\Models\ShiftSetting;
use App\Models\WorkDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $groups = Group::with(['shift', 'workDays', 'employees'])->get();
   
        if ($groups->isEmpty()) {
            return response()->json([
                'message' => 'No groups found.',
            ], 404);
        }

        return response()->json([
            'message' => 'Groups fetched successfully.',
            'groups' => GroupResource::collection($groups),
        ], 200);
    }

    public function add()
    {
        $workDays = WorkDay::select(['id', 'day_name'])->get();
        $shifts = ShiftSetting::select(['id', 'shift_name'])->get();
        $employees = Employee::whereDoesntHave('groups')->select(['id', 'profile_id', 'name'])->get();

        if ($employees->isEmpty()) {
            return response()->json([
                'message' => 'no_employees',
            ], 404);
        }

        if ($workDays->isEmpty() || $shifts->isEmpty()) {
            return response()->json([
                'message' => 'no_workdays_or_shifts',
            ], 404);
        }

        return response()->json([
            'workDays' => $workDays,
            'employees' => $employees,
            'shifts' => $shifts,
            'message' => 'success',
        ]);
    }

    public function details($id)
    {
        $group = Group::select(['id', 'group_name', 'description', 'shift_id','flexible_in_time','flexible_out_time'])
            ->with(['employees:id,name,profile_id,mobile_number,joining_date,present_address,picture,division_id,department_id,section_id,company_id', 'workDays:id,day_name', 'shift:id,shift_name',
                'employees.division:id,name', 'employees.department:id,name',
                'employees.section:id,name'])
            ->findOrFail($id);
        foreach ($group->employees as $item) {
            $item->setRelation('pivot', false);
            $item->makeHidden(['division_id', 'department_id', 'section_id']);
        };
        foreach ($group->workDays as $item) {
            $item->setRelation('pivot', false);
        };
        $group->makeHidden(['shift_id']);


        return response()->json([
            'message' => 'Group found.',
            'group' => $group,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group_name' => 'required|string|unique:groups,group_name',
            'description' => 'nullable|string',
            'shift_id' => 'required|exists:shift_settings,id',
            'status' => 'nullable|in:0,1',
            'flexInTime' => 'nullable|integer|between:1,59',
            'flexOutTime' => 'nullable|integer|between:1,59',
            'work_day_ids' => 'nullable|array',
            'work_day_ids.*' => 'exists:work_days,id',
            'employee_ids' => 'nullable|array',
            'employee_ids.*' => 'exists:employees,id',
        ], [
            'group_name.required' => 'Group name required',
            'group_name.unique' => 'This group name is already taken.',
            'shift_id.required' => 'Shift name required',
            'shift_id.exists' => 'Selected shift name does not exist',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
        $flexInTime = $request->filled('flexInTime') ? (int) $request->flexInTime : null;
        $flexOutTime = $request->filled('flexOutTime') ? (int) $request->flexOutTime : null;

        // Create the group
        $group = Group::create([
            'group_name' => $request->group_name,
            'description' => $request->description,
            'shift_id' => $request->shift_id,
            'status' => $request->status ?? 1,
            'flexible_in_time' => $flexInTime,
            'flexible_out_time' => $flexOutTime,
        ]);

        // Attach to pivot tables
        if (!empty($request->work_day_ids)) {
            $group->workDays()->sync($request->work_day_ids);
        }

        if (!empty($request->employee_ids)) {
            $group->employees()->sync($request->employee_ids);
        }

        return response()->json([
            'status' => true,
            'message' => 'Group created successfully.',
        ], 201);
    }

    public function edit($id)
    {
        $group = Group::select(['id', 'group_name', 'description', 'shift_id', 'status','flexible_in_time','flexible_out_time'])
            ->with(['employees:id,name,profile_id,mobile_number,joining_date,present_address,picture,division_id,department_id,section_id,company_id', 'workDays:id,day_name', 'shift:id,shift_name',
                'employees.division:id,name', 'employees.department:id,name',
                'employees.section:id,name'])
            ->findOrFail($id);
        foreach ($group->employees as $item) {
            $item->setRelation('pivot', false);
            $item->makeHidden(['division_id', 'department_id', 'section_id']);
        };
        foreach ($group->workDays as $item) {
            $item->setRelation('pivot', false);
        };
        // $group->makeHidden(['shift_id']);

        // \Log::info($group);

        $workDays = WorkDay::select(['id', 'day_name'])->get();
        $shifts = ShiftSetting::select(['id', 'shift_name'])->get();
        $employees = Employee::whereDoesntHave('groups')
            ->orWhereHas('groups', function ($q) use ($id) {
                $q->where('groups.id', $id);
            })
            ->select(['id', 'profile_id', 'name'])
            ->get();

        return response()->json([
            'message' => 'Group found.',
            'group' => $group,
            'shifts' => $shifts,
            'workDays' => $workDays,
            'employees' => $employees
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $group = Group::findOrFail($id);  // 404 if not found

        $validator = Validator::make($request->all(), [
            'group_name' => 'required|string|unique:groups,group_name,' . $group->id,
            'description' => 'nullable|string',
            'shift_id' => 'required|exists:shift_settings,id',
            'status' => 'nullable|in:0,1',
            'flexInTime' => 'nullable|integer|between:1,59',
            'flexOutTime' => 'nullable|integer|between:1,59',
            'work_day_ids' => 'nullable|array',
            'work_day_ids.*' => 'exists:work_days,id',
            'employee_ids' => 'nullable|array',
            'employee_ids.*' => 'exists:employees,id',
        ], [
            'group_name.required' => 'Group name required',
            'group_name.unique' => 'This group name is already taken.',
            'shift_id.required' => 'Shift name required',
            'shift_id.exists' => 'Selected shift name does not exist',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
        $flexInTime = $request->filled('flexInTime') ? (int) $request->flexInTime : null;
        $flexOutTime = $request->filled('flexOutTime') ? (int) $request->flexOutTime : null;
        // Update the group
        $group->update([
            'group_name' => $request->group_name,
            'description' => $request->description,
            'shift_id' => $request->shift_id,
            'status' => $request->status ?? 1,
            'flexible_in_time' => $flexInTime,
            'flexible_out_time' => $flexOutTime,
        ]);

        // Sync relationships
        $group->workDays()->sync($request->work_day_ids ?? []);
        $group->employees()->sync($request->employee_ids ?? []);

        return response()->json([
            'status' => true,
            'message' => 'Group updated successfully.',
        ], 200);
    }

    public function destroy(Group $group)
    {
      
        try {
            $group->employees()->detach();
            $group->workDays()->detach();
            $deleted = $group->delete();

            if (!$deleted) {
                return response()->json(['error' => 'Group could not be deleted.'], 500);
            } else {
                return response()->json(['message' => 'Group deleted successfully.']);
            }
        } catch (\Exception $e) {
            // \Log::error('Group deletion error: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    public function toggleStatus($id)
    {
        $group = Group::findOrFail($id);

        $group->status = $group->status === 1 ? 0 : 1;
        $group->save();

        return response()->json([
            'status' => $group->status,
            'badge_class' => $group->status === 1 ? 'bg-success' : 'bg-secondary'
        ]);
    }
}
