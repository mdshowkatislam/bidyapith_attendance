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
use Barryvdh\DomPDF\Facade\PDF;

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
        // $groups = Group::with([
        //     'shift.branch',  // Eager load branch through shift
        //     'workDays',
        //     'employees',
        // ])->get();



        $groups = Group::all();

        if ($groups->isEmpty()) {
            return $this->apisuccessResponse('No groups found.', 404);
        }
        return $this->apisuccessResponse('Groups fetched successfully.', GroupResource::collection($groups), 200);
    }

    public function add()
    {

        $branches = Branch::where('rec_status', 1)
            ->select('branch_code', 'branch_name_en')
            ->get();

        $shifts = collect();

        $workDays = WorkDay::select('id', 'day_name')->get();
        $employees = Employee::whereDoesntHave('groups')->select('id', 'profile_id')->get();

        if ($employees->isEmpty()) {
            return $this->apisuccessResponse('No employees available to assign.', 404);
        }

        if ($workDays->isEmpty()) {
            return $this->apisuccessResponse('No work days available to assign.', 404);
        }

        return $this->apisuccessResponse('Data fetched successfully.', [
            'workDays' => $workDays,
            'employees' => $employees,
            'shifts' => $shifts,
            'branches' => $branches,
        ]);
    }

    public function details($id)
    {
        // exit();

        $group = Group::where('status', 1)->find($id);
        $result = new GroupResource($group);


        if (!$group) {
            return $this->apisuccessResponse('Group not found.', 404);
        }

        return $this->apisuccessResponse('Group found.', new GroupResource($group), 200);
    }

    public function store(Request $request)
    {
      
        $validator = Validator::make($request->all(), [
            'group_name' => 'required|string|unique:groups,group_name',
            'description' => 'nullable|string',
            'branch_id' => 'required|integer',
            'shift_id' => 'required|integer', // from external API
            'status' => 'nullable|in:0,1',
            'flexible_in_time' => 'nullable|integer|between:1,59',
            'flexible_out_time' => 'nullable|integer|between:1,59',
            'work_day_ids' => 'nullable|array',
            'work_day_ids.*' => 'integer|exists:work_days,id',
            'employee_emp_ids' => 'nullable|array',
            'employee_emp_ids.*' => 'integer', // from external API
        ], [
            'group_name.required' => 'Group name is required',
            'group_name.unique' => 'This group name is already taken.',
            'branch_id.required' => 'Branch is required',
            'shift_id.required' => 'Shift selection required',
        ]);

        if ($validator->fails()) {
            return $this->apierrorResponse(new \Exception($validator->errors()->first()), 'Validation error', 422);
        }

        try {
            // convert to int if provided
            $flexInTime = $request->filled('flexible_in_time') ? (int) $request->flexible_in_time : null;
            $flexOutTime = $request->filled('flexible_out_time') ? (int) $request->flexible_out_time : null;

            // Create the group
            $group = Group::create([
                'group_name' => $request->group_name,
                'description' => $request->description,
                'branch_id' => $request->branch_id,
                'shift_id' => $request->shift_id,
                'status' => $request->status ?? 1,
                'flexible_in_time' => $flexInTime,
                'flexible_out_time' => $flexOutTime,
            ]);

            // Pivot relationships
            if (!empty($request->work_day_ids)) {
                $group->workDays()->sync($request->work_day_ids);

            }
            // Employees come from external API — store just IDs in pivot
            if (!empty($request->employee_emp_ids)) {
                $group->employees()->sync($request->employee_emp_ids); 
            }

            return $this->apisuccessResponse('Group created successfully.', $group, 201);
        } catch (\Exception $e) {
            return $this->apierrorResponse(
                new \Exception('Error creating group: ' . $e->getMessage()),
                'Error creating group',
                500
            );
        }
    }


    public function edit($id)
{
    $group = Group::select([
            'id',
            'group_name',
            'description',
            'branch_id',
            'shift_id',
            'status',
            'flexible_in_time',
            'flexible_out_time'
        ])
        ->with([
            'employees:id,name_en,profile_id,image', // local fallback — or empty if external
            'workDays:id,day_name'
        ])
        ->findOrFail($id);

    // Remove pivot relations for cleaner JSON
    foreach ($group->employees as $item) {
        $item->setRelation('pivot', false);
    }
    foreach ($group->workDays as $item) {
        $item->setRelation('pivot', false);
    }

    // Local workdays for dropdown
    $workDays = WorkDay::select(['id', 'day_name'])->get();

    return $this->apisuccessResponse('Data fetched successfully.', [
        'group' => $group,
        'workDays' => $workDays,
        // The frontend should call external APIs for these:
        'branches' => [],
        'shifts' => [],
        'employees' => []
    ]);
}


    public function update($id, Request $request)
{
    $group = Group::findOrFail($id);

    $validator = Validator::make($request->all(), [
        'group_name' => 'required|string|unique:groups,group_name,' . $group->id,
        'description' => 'nullable|string',
        'branch_id' => 'required|integer',
        'shift_id' => 'required|integer', // external API
        'status' => 'nullable|in:0,1',
        'flexible_in_time' => 'nullable|integer|between:1,59',
        'flexible_out_time' => 'nullable|integer|between:1,59',
        'work_day_ids' => 'nullable|array',
        'work_day_ids.*' => 'exists:work_days,id',
        'employee_emp_ids' => 'nullable|array',
        'employee_emp_ids.*' => 'integer', // external API
    ], [
        'group_name.required' => 'Group name is required',
        'group_name.unique' => 'This group name is already taken.',
        'branch_id.required' => 'Branch is required',
        'shift_id.required' => 'Shift selection required',
    ]);

    if ($validator->fails()) {
        return $this->apierrorResponse(new \Exception($validator->errors()->first()), 'Validation error', 422);
    }

    try {
        $group->update([
            'group_name' => $request->group_name,
            'description' => $request->description,
            'branch_id' => $request->branch_id,
            'shift_id' => $request->shift_id,
            'status' => $request->status ?? 1,
            'flexible_in_time' => $request->flexible_in_time,
            'flexible_out_time' => $request->flexible_out_time,
        ]);

        $group->workDays()->sync($request->work_day_ids ?? []);
        $group->employees()->sync($request->employee_emp_ids ?? []);

        return $this->apisuccessResponse('Group updated successfully.', $group, 200);
    } catch (\Exception $e) {
        return $this->apierrorResponse(new \Exception('Error updating group: ' . $e->getMessage()), 'Error updating group', 500);
    }
}


   public function destroy(Group $group)
{
    try {
        $group->employees()->detach();
        $group->workDays()->detach();

        if (!$group->delete()) {
            return $this->apierrorResponse(new \Exception('Group could not be deleted.'), 'Group deletion error', 500);
        }

        return $this->apisuccessResponse('Group deleted successfully.', null, 200);
    } catch (\Exception $e) {
        return $this->apierrorResponse(new \Exception('Error deleting group: ' . $e->getMessage()), 'Group deletion error', 500);
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
    //For defalt Paper Size view
    // public function downloadPdf($id)
    // {
    //     try {
    //         $data = Group::where('status', 1)->find($id);

    //         if (!$data) {
    //             return $this->apierrorResponse(new \Exception('Group not found'), 'Group not found', 404);
    //         }

    //         $group = new GroupResource($data);

    //         // Configure PDF settings
    //         $pdf = PDF::loadView('admin.frontend.group.pdf', compact('group'))
    //                   ->setPaper('a4', 'landscape') // or 'portrait' depending on your content
    //                   ->setOption('dpi', 150)
    //                   ->setOption('margin-top', 10)
    //                   ->setOption('margin-right', 10)
    //                   ->setOption('margin-bottom', 10)
    //                   ->setOption('margin-left', 10);

    //         // Return the PDF as a download response
    //         return $pdf->download('group-details-' . $id . '.pdf');
    //     } catch (\Exception $e) {
    //         return $this->apierrorResponse($e, 'Failed to generate PDF', 500);
    //     }
    // }


    //For Custom Paper Size with Specific Dimensions
    public function downloadPdf($id)
    {
        try {
            $data = Group::where('status', 1)->find($id);

            if (!$data) {
                return $this->apierrorResponse(new \Exception('Group not found'), 'Group not found', 404);
            }

            $group = new GroupResource($data);
            Log::info(json_encode($group->toArray(request()), JSON_PRETTY_PRINT));

            // Custom paper size (width, height) in millimeters
            $pdf = PDF::loadView('admin.frontend.group.pdf', compact('group'))
                ->setPaper([0, 0, 1200, 1440], 'portrait') // Custom size
                ->setOption('dpi', 96)
                ->setOption('defaultFont', 'Arial')
                ->setOption('isHtml5ParserEnabled', true);
            //   return $pdf->stream('group-details.pdf');

            return $pdf->download('group-details-' . $id . '.pdf');
        } catch (\Exception $e) {
            return $this->apierrorResponse($e, 'Failed to generate PDF', 500);
        }
    }

    // public function downloadPdf($id)
    // {
    //     try {
    //         $data = Group::where('status', 1)->find($id);

    //       $group = new GroupResource($data);
    //             //   \Log::info('Group data for PDF:', $group->toArray(request()));
    //         $pdf = PDF::loadView('admin.frontend.group.pdf', compact('group'));

    //         // return $pdf->download('group-details-' . $id . '.pdf');
    //         // return $pdf->stream('group-details-' . $id . '.pdf');
    //         // return response()->streamDownload(function() use ($pdf) {
    //         //     echo $pdf->output();
    //         // }, 'group-details-' . $id . '.pdf');
    //         // return response()->json([
    //         //     'message' => 'PDF generated successfully.',
    //         //     'pdf_content' => base64_encode($pdf->output()),
    //         //     'file_name' => 'group-details-' . $id . '.pdf'
    //         // ]);
    //         return response()->json([
    //             'group' => $pdf->output(),
    //             'file_name' => 'group-details-' . $id . '.pdf'
    //             ]);

    //     } catch (\Exception $e) {
    //         return $this->apierrorResponse($e, 'Failed to generate PDF', 500);
    //     }
    // }
}
