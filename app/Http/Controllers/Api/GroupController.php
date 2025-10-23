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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Response;

class GroupController extends Controller
{
    use \App\Traits\ApiResponse; // Use your existing trait

    public function index(Request $request)
    {
        // Eager load relationships to avoid N+1 queries
        $groups = Group::with(['employees', 'workDays'])->get();
        
        Log::info('Groups fetched count: ' . $groups->count());

        if ($groups->isEmpty()) {
            return $this->errorResponse('No groups found.', Response::HTTP_NOT_FOUND);
        }

        return $this->successResponseWithData(
            GroupResource::collection($groups),
            'Groups fetched successfully.',
            Response::HTTP_OK
        );
    }

    public function show($id)
    {
        $group = Group::with(['employees', 'workDays'])->find($id);

        if (!$group) {
            return $this->errorResponse('Group not found.', Response::HTTP_NOT_FOUND);
        }

        return $this->successResponseWithData(
            new GroupResource($group),
            'Group fetched successfully.',
            Response::HTTP_OK
        );
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
            return $this->errorResponse('No employees available to assign.', Response::HTTP_NOT_FOUND);
        }

        if ($workDays->isEmpty()) {
            return $this->errorResponse('No work days available to assign.', Response::HTTP_NOT_FOUND);
        }

        return $this->successResponseWithData([
            'workDays' => $workDays,
            'employees' => $employees,
            'shifts' => $shifts,
            'branches' => $branches,
        ], 'Data fetched successfully.', Response::HTTP_OK);
    }

    public function details($id)
    {
        $group = Group::where('status', 1)->find($id);

        if (!$group) {
            return $this->errorResponse('Group not found.', Response::HTTP_NOT_FOUND);
        }

        return $this->successResponseWithData(
            new GroupResource($group),
            'Group found.',
            Response::HTTP_OK
        );
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group_name' => 'required|string|unique:groups,group_name',
            'description' => 'nullable|string',
            'branch_uid' => 'required|integer',
            'shift_uid' => 'required|integer',
            'status' => 'nullable|in:0,1',
            'flexible_in_time' => 'nullable|integer|between:1,59',
            'flexible_out_time' => 'nullable|integer|between:1,59',
            'work_day_ids' => 'nullable|array',
            'work_day_ids.*' => 'integer|exists:work_days,id',
            'employee_emp_ids' => 'nullable|array',
            'employee_emp_ids.*' => 'integer',
        ], [
            'group_name.required' => 'Group name is required',
            'group_name.unique' => 'This group name is already taken.',
            'branch_uid.required' => 'Branch UID required',
            'shift_uid.required' => 'Shift selection (UID) required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $flexInTime = $request->filled('flexible_in_time') ? (int) $request->flexible_in_time : null;
            $flexOutTime = $request->filled('flexible_out_time') ? (int) $request->flexible_out_time : null;

            $group = Group::create([
                'group_name' => $request->group_name,
                'description' => $request->description,
                'branch_uid' => $request->branch_uid,
                'shift_uid' => $request->shift_uid,
                'status' => $request->status ?? 1,
                'flexible_in_time' => $flexInTime,
                'flexible_out_time' => $flexOutTime,
            ]);

            if (!empty($request->work_day_ids)) {
                $group->workDays()->sync($request->work_day_ids);
            }

            if (!empty($request->employee_emp_ids)) {
                $group->employees()->sync($request->employee_emp_ids);
            }

            return $this->successResponseWithData($group, 'Group created successfully.', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->errorResponse('Error creating group: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function edit($id)
    {
        $baseUrl = rtrim(config('api_url.baseUrl_1'), '/');

        $group = Group::select([
            'id',
            'group_name',
            'description',
            'branch_uid',
            'shift_uid',
            'status',
            'flexible_in_time',
            'flexible_out_time'
        ])
            ->with([
                'employees:id,caid,profile_id,person_type',
                'workDays:id,day_name'
            ])
            ->findOrFail($id);

        foreach ($group->employees as $item) {
            $item->setRelation('pivot', false);
        }
        foreach ($group->workDays as $item) {
            $item->setRelation('pivot', false);
        }

        $branches = $this->fetchBranchesList($baseUrl);
        $shifts = $this->fetchShiftsList($baseUrl);

        $employees = $group->employees->map(function ($employee) use ($baseUrl) {
            $personType = $employee->person_type;
            $profileId = $employee->profile_id;

            $employeeData = $this->fetchEmployeeDetails($baseUrl, $personType, $profileId);

            return [
                'id' => $employee->id,
                'profile_id' => $profileId,
                'person_type' => $personType,
                'name' => $employeeData['name_en'] ?? null,
                'designation' => $employeeData['designation'] ?? null,
                'mobile_number' => $employeeData['mobile_no'] ?? null,
                'present_address' => $employeeData['address'] ?? null,
                'picture' => $employeeData['image'] ?? null,
                'division' => $employeeData['division_id'] ?? null,
                'district' => $employeeData['district_id'] ?? null,
                'upazila' => $employeeData['upazilla_id'] ?? null,
            ];
        });

        $workDays = WorkDay::select(['id', 'day_name'])->get();

        return $this->successResponseWithData([
            'group' => $group,
            'workDays' => $workDays,
            'branches' => $branches,
            'shifts' => $shifts,
            'employees' => $employees,
        ], 'Data fetched successfully.', Response::HTTP_OK);
    }

    private function fetchEmployeeDetails($baseUrl, $personType, $profileId)
    {
        $endpoint = match ($personType) {
            1 => 'teacherAsEmp',
            2 => 'staffAsEmp',
            3 => 'studentAsEmp',
            default => null,
        };

        if (!$endpoint) return [];

        $url = "{$baseUrl}/api/v3/{$endpoint}/{$profileId}";
        Log::info("Fetching employee from URL: {$url}");

        try {
            $response = Http::timeout(6)->get($url);
            if ($response->successful()) {
                return $response->json('data') ?? [];
            }
            Log::warning('Employee API failed', [
                'url' => $url,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Employee API exception', ['url' => $url, 'error' => $e->getMessage()]);
        }

        return [];
    }

    private function fetchBranchesList($baseUrl)
    {
        $eiin = 134172;
        $url = "{$baseUrl}/api/v3/branch-list?eiin={$eiin}";
        Log::info("Fetching branches list from URL: {$url}");

        try {
            $response = Http::timeout(6)->get($url);
            Log::info('Branches API Raw', ['body' => $response->json()]);
            if ($response->successful()) {
                $data = $response->json('data') ?? [];
                Log::info('Branch API Response Count: ' . count($data));
                return is_array($data) ? $data : [];
            }
        } catch (\Throwable $e) {
            Log::error('Error fetching branches list', ['url' => $url, 'error' => $e->getMessage()]);
        }

        return [];
    }

    private function fetchShiftsList($baseUrl)
    {
        $eiin = 134172;
        $url = "{$baseUrl}/api/v3/test/shift-list?eiin={$eiin}";
        Log::info("zzz: {$url}");

        try {
            $response = Http::timeout(6)->get($url);
            Log::info('Shift API Raw', ['body' => $response->json()]);
            if ($response->successful()) {
                $data = $response->json('data') ?? [];
                Log::info('Shifts API Response Count: ' . count($data));
                return is_array($data) ? $data : [];
            }
        } catch (\Throwable $e) {
            Log::error('Error fetching shifts list', ['url' => $url, 'error' => $e->getMessage()]);
        }

        return [];
    }

    public function update($id, Request $request)
    {
        $group = Group::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'group_name' => 'required|string|unique:groups,group_name,' . $group->id,
            'description' => 'nullable|string',
            'branch_uid' => 'required|integer',
            'shift_uid' => 'required|integer',
            'status' => 'nullable|in:0,1',
            'flexible_in_time' => 'nullable|integer|between:1,59',
            'flexible_out_time' => 'nullable|integer|between:1,59',
            'work_day_ids' => 'nullable|array',
            'work_day_ids.*' => 'exists:work_days,id',
            'employee_emp_ids' => 'nullable|array',
            'employee_emp_ids.*' => 'integer',
        ], [
            'group_name.required' => 'Group name is required',
            'group_name.unique' => 'This group name is already taken.',
            'branch_uid.required' => 'Branch is required',
            'shift_uid.required' => 'Shift selection required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $group->update([
                'group_name' => $request->group_name,
                'description' => $request->description,
                'branch_uid' => $request->branch_uid,
                'shift_uid' => $request->shift_uid,
                'status' => $request->status ?? 1,
                'flexible_in_time' => $request->flexible_in_time,
                'flexible_out_time' => $request->flexible_out_time,
            ]);

            $group->workDays()->sync($request->work_day_ids ?? []);
            $group->employees()->sync($request->employee_emp_ids ?? []);

            return $this->successResponseWithData($group, 'Group updated successfully.', Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->errorResponse('Error updating group: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Group $group)
    {
        try {
            $group->employees()->detach();
            $group->workDays()->detach();

            if (!$group->delete()) {
                return $this->errorResponse('Group could not be deleted.', Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->successMessage('Group deleted successfully.', Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->errorResponse('Error deleting group: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
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


    public function downloadPdf($id)
    {
        Log::info('pp');
        try {
            $data = Group::where('status', 1)->find($id);

            if (!$data) {
                return $this->errorResponse('Group not found', Response::HTTP_NOT_FOUND);
            }

            $groupResource = new GroupResource($data);
            $groupArray = $groupResource->toArray(request());
            
            Log::info('Final data being passed to PDF:');
            Log::info(json_encode($groupArray, JSON_PRETTY_PRINT));

            $pdf = PDF::loadView('admin.frontend.group.pdf', ['group' => $groupArray])
                ->setPaper([0, 0, 1200, 1440], 'portrait')
                ->setOption('dpi', 96)
                ->setOption('defaultFont', 'Arial')
                ->setOption('isHtml5ParserEnabled', true);
            
            Log::info('PDF generated successfully');
            return $pdf->download('group-details-' . $id . '.pdf');
        } catch (\Exception $e) {
            Log::error('PDF Generation Error: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            return $this->errorResponse('Failed to generate PDF: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function downloadPdf2($id)
    {
        try {
            $data = Group::where('status', 1)->find($id);

            $group = new GroupResource($data);
            
            $pdf = PDF::loadView('admin.frontend.group.pdf', compact('group'));

            return response()->json([
                'group' => base64_encode($pdf->output()),
                'file_name' => 'group-details-' . $id . '.pdf'
            ]);

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to generate PDF: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function downloadPdf3($id)
    {
        try {
            $data = Group::where('status', 1)->find($id);

            if (!$data) {
                return $this->errorResponse('Group not found', Response::HTTP_NOT_FOUND);
            }

            $group = new GroupResource($data);

            $pdf = PDF::loadView('admin.frontend.group.pdf', compact('group'))
                      ->setPaper('a4', 'landscape')
                      ->setOption('dpi', 150)
                      ->setOption('margin-top', 10)
                      ->setOption('margin-right', 10)
                      ->setOption('margin-bottom', 10)
                      ->setOption('margin-left', 10);

            return $pdf->download('group-details-' . $id . '.pdf');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to generate PDF: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}