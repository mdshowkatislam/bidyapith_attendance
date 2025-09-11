<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GroupResource;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\Group;
use App\Models\ShiftSetting;
use App\Models\WorkDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class GroupController extends Controller
{
    // public function index(Request $request)
    // {
    //     $groups = Group::with([
    //         'shift:id,shift_name_en,branch_code',
    //         'shift.branch:id,branch_code,branch_name_en',  // Add branch relationship
    //         'workDays:id,day_name',
    //         'employees:id,name'
    //     ])
    //         ->get();

    //     if ($groups->isEmpty()) {
    //         return response()->json([
    //             'message' => 'No groups found.',
    //         ], 404);
    //     }

    //     return response()->json([
    //         'message' => 'Groups fetched successfully.',
    //         'groups' => $groups,
    //     ], 200);
    // }

    public function index(Request $request)
    {
        $groups = Group::with([
            'shift.branch',  // Eager load branch through shift
            'workDays',
            'employees'
        ])
            ->get();

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
        // Get all active branches
        $branches = Branch::where('rec_status', 1)
            ->select('branch_code', 'branch_name_en')
            ->get();

        $shifts = collect();
        $workDays = WorkDay::select('id', 'day_name')->get();
        $employees = Employee::whereDoesntHave('groups')->select('id', 'profile_id', 'name')->get();

        // \Log::info($workDays);

        if ($employees->isEmpty()) {
            return response()->json([
                'message' => 'no_employees',
            ], 404);
        }

        if ($workDays->isEmpty() ) {
            return response()->json([
                'message' => 'no_workdays_or_shifts',
            ], 404);
        }

        return response()->json([
            'workDays' => $workDays,
            'employees' => $employees,
            'shifts' => $shifts,
            'message' => 'success',
            'branches' => $branches,
        ]);
    }

    public function details($id)
    {
        $group = Group::select(['id', 'group_name', 'description', 'shift_id', 'flexible_in_time', 'flexible_out_time'])
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
            'branch_code' => 'required|integer|exists:branches,branch_code',
            'shift_id' => [
                'required',
                'integer',
                Rule::exists('shift_settings', 'id')->where(function ($query) use ($request) {
                    $query->where('branch_code', $request->branch_code);
                })
            ],
            'status' => 'nullable|in:0,1',
            'flexible_in_time' => 'nullable|integer|between:1,59',
            'flexible_out_time' => 'nullable|integer|between:1,59',
            'work_day_ids' => 'nullable|array',
            'work_day_ids.*' => 'exists:work_days,id',
            'employee_ids' => 'nullable|array',
            'employee_ids.*' => 'exists:employees,id',
        ], [
            'group_name.required' => 'Group name required',
            'group_name.unique' => 'This group name is already taken.',
            'branch_code.required' => 'Branch selection required',
            'branch_code.exists' => 'Selected branch does not exist',
            'shift_id.required' => 'Shift selection required',
            'shift_id.exists' => 'Selected shift does not exist for this branch',
        ]);

        if ($validator->fails()) {
            
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
 

        try {
            $flexInTime = $request->filled('flexible_in_time') ? (int) $request->flexible_in_time : null;
            $flexOutTime = $request->filled('flexible_out_time') ? (int) $request->flexible_out_time : null;

            // Create the group (NO branch_code stored in groups table)
            $group = Group::create([
                'group_name' => $request->group_name,
                'description' => $request->description,
                'shift_id' => $request->shift_id,
                'status' => $request->status ?? 1,
                'flexible_in_time' => $flexInTime,
                'flexible_out_time' => $flexOutTime,
            ]);

            // Attach to pivot tables (use sync instead of attach to handle updates)
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
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error creating group: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $group = Group::select(['id', 'group_name', 'description', 'shift_id', 'status', 'flexible_in_time', 'flexible_out_time'])
            ->with([
                'employees:id,name,profile_id,mobile_number,joining_date,present_address,picture,division_id,department_id,section_id,company_id',
                'workDays:id,day_name',
                'shift:id,shift_name_en,branch_code',
                'shift.branch:id,branch_code,branch_name_en',  // Add branch relationship
                'employees.division:id,name',
                'employees.department:id,name',
                'employees.section:id,name'
            ])
            ->findOrFail($id);

        foreach ($group->employees as $item) {
            $item->setRelation('pivot', false);
            $item->makeHidden(['division_id', 'department_id', 'section_id']);
        };

        foreach ($group->workDays as $item) {
            $item->setRelation('pivot', false);
        };

        $workDays = WorkDay::select(['id', 'day_name'])->get();

        // Get all branches for selection
        $branches = Branch::where('rec_status', 1)
            ->select('branch_code', 'branch_name_en')
            ->get();

        // Get shifts for the current group's branch
        $shifts = ShiftSetting::where('branch_code', $group->shift->branch_code ?? null)
            ->select('id', 'shift_name_en')
            ->get();

        $employees = Employee::whereDoesntHave('groups')
            ->orWhereHas('groups', function ($q) use ($id) {
                $q->where('groups.id', $id);
            })
            ->select(['id', 'profile_id', 'name'])
            ->get();

        return response()->json([
            'message' => 'Group found.',
            'group' => $group,
            'branches' => $branches,
            'shifts' => $shifts,
            'workDays' => $workDays,
            'employees' => $employees
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $group = Group::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'group_name' => 'required|string|unique:groups,group_name,' . $group->id,
            'description' => 'nullable|string',
            'branch_code' => 'required|integer|exists:branches,branch_code',
            'shift_id' => [
                'required',
                'integer',
                Rule::exists('shift_settings', 'id')->where(function ($query) use ($request) {
                    $query->where('branch_code', $request->branch_code);
                })
            ],
            'status' => 'nullable|in:0,1',
            'flexible_in_time' => 'nullable|integer|between:1,59',
            'flexible_out_time' => 'nullable|integer|between:1,59',
            'work_day_ids' => 'nullable|array',
            'work_day_ids.*' => 'exists:work_days,id',
            'employee_ids' => 'nullable|array',
            'employee_ids.*' => 'exists:employees,id',
        ], [
            'group_name.required' => 'Group name required',
            'group_name.unique' => 'This group name is already taken.',
            'branch_code.required' => 'Branch selection required',
            'branch_code.exists' => 'Selected branch does not exist',
            'shift_id.required' => 'Shift selection required',
            'shift_id.exists' => 'Selected shift does not exist for this branch',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $flexInTime = $request->filled('flexible_in_time') ? (int) $request->flexible_in_time : null;
            $flexOutTime = $request->filled('flexible_out_time') ? (int) $request->flexible_out_time : null;

            // Update the group (NO branch_code stored in groups table)
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
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error updating group: ' . $e->getMessage()
            ], 500);
        }
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
