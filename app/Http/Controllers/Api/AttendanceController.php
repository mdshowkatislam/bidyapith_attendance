<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Group;
use App\Models\Holiday;
use App\Services\ExternalDataService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    protected $externalDataService;

    public function __construct(ExternalDataService $externalDataService)
    {
        $this->externalDataService = $externalDataService;
    }

    public function index(Request $request)
    {
        try {
            // Validate only local fields
            $validated = $request->validate([
                'branch_uid' => 'required',
                'shift_uid' => 'required',
                'date_range' => 'nullable|string|required_without:month',
                'month' => 'nullable|date_format:Y-m|required_without:date_range',
                'upazila_id' => 'nullable|integer',
                'district_id' => 'nullable|integer',
                'division_id' => 'nullable|integer',
                'group_id' => 'nullable|integer|exists:groups,id',
            ]);

            // Parse date range or month
            [$start, $end] = $this->getDateRangeFromRequest($validated);

            // Get shift details from external service
            $shift = $this->externalDataService->fetchShiftDetails($validated['shift_uid']);

            if (!$shift) {
                return response()->json(['error' => 'Shift not found'], 404);
            }

            // Get branch details from external service
            $branch = $this->externalDataService->fetchBranchDetails($validated['branch_uid']);
            if (!$branch) {
                return response()->json(['error' => 'Branch not found'], 404);
            }

            // Get employees filtered by branch, shift and other criteria
            $employees = $this->getFilteredEmployees($validated, $branch, $shift);

            // Get attendance records for date range from external service
            $attendanceData = $this->externalDataService->fetchAttendanceData(
                $employees->pluck('profile_id')->toArray(),
                $start->toDateString(),
                $end->toDateString()
            );

            // Build results for each date
            $results = $this->buildAttendanceResults(
                $employees,
                $attendanceData,
                $start,
                $end,
                $shift,
                $validated['branch_uid'],
                $validated['shift_uid']
            );

            // Determine type
            $type = $this->getReportType($validated, $start, $end);

            return response()->json([
                'type' => $type,
                'branch' => $branch,
                'shift' => $shift,
                'results' => $results,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in attendance index method: ' . $e->getMessage());
            Log::error('Full error trace: ' . $e->getTraceAsString());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Extract date range from request (either date_range or month)
     */
    private function getDateRangeFromRequest(array $validated): array
    {
        if (!empty($validated['date_range']) && str_contains($validated['date_range'], ' - ')) {
            [$start, $end] = explode(' - ', $validated['date_range'], 2);
            return [
                Carbon::parse(trim($start)),
                Carbon::parse(trim($end))
            ];
        }

        if (!empty($validated['month'])) {
            [$year, $monthNum] = explode('-', $validated['month']);
            $start = Carbon::createFromDate($year, $monthNum, 1)->startOfMonth();
            $end = $start->copy()->endOfMonth();
            return [$start, $end];
        }

        abort(400, 'Either date_range or month must be provided.');
    }

    /** Get employees filtered by branch, shift and other criteria */

    /**
     * Get employees filtered by branch, shift and other criteria
     */
    private function getFilteredEmployees(array $validated, array $branch, array $shift)
    {
        // 1️⃣ Fetch groups for this branch + shift
        $query = Group::where('branch_uid', $validated['branch_uid'])
            ->where('shift_uid', $validated['shift_uid']);

        if (!empty($validated['group_id'])) {
            $query->where('id', $validated['group_id']);
        }

        $groups = $query->get();
        $groupIds = $groups->pluck('id')->toArray();

        if (empty($groupIds)) {
            Log::warning("No groups found for branch_uid: {$validated['branch_uid']} and shift_uid: {$validated['shift_uid']}");
            return collect();
        }

        // 2️⃣ Prepare filters to send to the external system
        $employeeProfileIds = DB::table('employee_group')
            ->whereIn('group_id', $groupIds)
            ->pluck('employee_emp_id')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        if (empty($employeeProfileIds)) {
            Log::warning('No employees found in pivot table for groups', ['group_ids' => $groupIds]);
            return collect();
        }

        $filterPayload = [
            'person_type' => 1,  // teachers; you can generalize later for staff/student
            'district_id' => $validated['district_id'] ?? null,
            'division_id' => $validated['division_id'] ?? null,
            'upazila_id' => $validated['upazila_id'] ?? null,
            'profile_ids' => $employeeProfileIds,
        ];

        // 3️⃣ Call external API
        $externalFiltered = $this->externalDataService->fetchFilteredEmployees($filterPayload);


        if (empty($externalFiltered)) {
            Log::info('No employees matched external filters', $filterPayload);
            return collect();
        }

        // 4️⃣ Match local employees based on returned profile IDs
        $employees = Employee::whereIn('profile_id', $externalFiltered)->get();

        Log::info('bbb');
        Log::info('Filtered employees count: ' . $employees->count());

        // 5️⃣ Enrich employee data using external service
        return $employees->map(function ($employee) use ($groups) {
            $employeeGroup = $employee->groups->first();

            if (!$employeeGroup) {
                Log::warning("Employee {$employee->id} has no group association");
                return null;
            }

            $employeeData = $this->externalDataService->fetchEmployeeDetails(
                $employee->person_type,
                $employee->profile_id
            );

            return (object) [
                'id' => $employee->id,
                'profile_id' => $employee->profile_id,
                'name' => $employeeData['name_en'] ?? $employee->name,
                'person_type' => $employee->person_type,
                'group_id' => $employeeGroup->id,
                'group_name' => $employeeGroup->group_name,
                'start_time' => $employeeGroup->shift->shift_start_time ?? '09:00',
                'end_time' => $employeeGroup->shift->shift_end_time ?? '17:00',
                'flexible_in_time' => $employeeGroup->flexible_in_time ?? 0,
                'flexible_out_time' => $employeeGroup->flexible_out_time ?? 0,
                'division_name_en' => isset($employeeData['division_id'])
                    ? $this->externalDataService->fetchDivisionName($employeeData['division_id'])
                    : null,
                'district_name_en' => isset($employeeData['district_id'])
                    ? $this->externalDataService->fetchDistrictName($employeeData['district_id'])
                    : null,
                'upazila_name_en' => isset($employeeData['upazilla_id'])
                    ? $this->externalDataService->fetchUpazilaName($employeeData['upazilla_id'])
                    : null,
                'division_id' => $employeeData['division_id'] ?? null,
                'district_id' => $employeeData['district_id'] ?? null,
                'upazila_id' => $employeeData['upazilla_id'] ?? null,
            ];
        })->filter();
    }

    /**
     * Build final attendance results
     */
    private function buildAttendanceResults($employees, $attendanceData, Carbon $start, Carbon $end, array $shift, int $branchId, int $shiftId)
    {
        $results = [];

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $currentDate = $date->toDateString();
            $dayName = $date->format('l');

            // Determine if it's a working day
            $workdayInfo = $this->checkWorkday($branchId, $shiftId, $dayName, $currentDate);
            if ($workdayInfo['status'] !== 'Working Day') {
                $results[] = array_merge([
                    'date' => $currentDate,
                    'shift_name' => $shift['shift_name_en'] ?? 'Unknown Shift',
                    'attendance' => []
                ], $workdayInfo);
                continue;
            }

            // Build daily attendance
            $dailyAttendance = $this->buildDailyAttendance($employees, $attendanceData, $currentDate);

            $results[] = [
                'date' => $currentDate,
                'shift_name' => $shift['shift_name_en'] ?? 'Unknown Shift',
                'attendance' => $dailyAttendance,
                'status' => 'Working Day',
                'holiday_name' => null,
                'description' => null
            ];
        }

        return $results;
    }

    /**
     * Check if a day is working or holiday
     */
    private function checkWorkday(int $branchId, int $shiftId, string $dayName, string $currentDate): array
    {
        // Get work day configuration
        $workDay = DB::table('work_days')->where('day_name', $dayName)->first();
        if (!$workDay) {
            return [
                'status' => 'Invalid day name',
                'holiday_name' => null,
                'description' => "$dayName not defined in work_days table"
            ];
        }
        Log::info('999');
        Log::info($branchId);
        $weekdayId = $workDay->id;

        // Get groups for this branch and shift
        $groups = Group::where('branch_uid', $branchId)
            ->where('shift_uid', $shiftId)
            ->get();
        $groupIds = $groups->pluck('id')->toArray();

        if (empty($groupIds)) {
            return [
                'status' => 'No groups found',
                'holiday_name' => null,
                'description' => 'No groups configured for this branch and shift'
            ];
        }

        // Check if this weekday is working for any of the groups
        $groupWorkDays = DB::table('work_day_group')
            ->whereIn('group_id', $groupIds)
            ->pluck('work_day_id', 'group_id')
            ->toArray();

        $weekdayIsWorkingDay = in_array($weekdayId, $groupWorkDays);

        // Check for special working days
        $specialWorkingDates = DB::table('group_special_workdays')
            ->join('special_working_days', 'special_working_days.id', '=', 'group_special_workdays.special_workingday_id')
            ->whereIn('group_special_workdays.group_id', $groupIds)
            ->whereDate('special_working_days.date', $currentDate)
            ->exists();

        if ($specialWorkingDates) {
            $weekdayIsWorkingDay = true;
        }

        if (!$weekdayIsWorkingDay) {
            return [
                'status' => 'Weekend Holiday',
                'holiday_name' => 'Weekly Off',
                'description' => "$dayName is a non-working day for this branch/shift"
            ];
        }

        // Holiday check
        $isHoliday = Holiday::where('status', 1)
            ->where('branch_uid', $branchId)
            ->whereDate('start_date', '<=', $currentDate)
            ->where(function ($query) use ($currentDate) {
                $query
                    ->whereDate('end_date', '>=', $currentDate)
                    ->orWhereNull('end_date');
            })
            ->first();

        if ($isHoliday) {
            return [
                'status' => 'This is Holiday',
                'holiday_name' => $isHoliday->holiday_name,
                'description' => $isHoliday->description
            ];
        }

        return ['status' => 'Working Day', 'holiday_name' => null, 'description' => null];
    }

    /**
     * Build daily attendance records for all employees for a given date
     */
    private function buildDailyAttendance($employees, $attendanceData, string $currentDate)
    {
        $dailyAttendance = $employees->map(function ($emp) use ($attendanceData, $currentDate) {
            // This would use the actual attendance data from external service
            // For now, marking all as absent as placeholder
            return $this->absentRecord($emp, $currentDate);
        });

        return $dailyAttendance->values()->all();
    }

    private function absentRecord($emp, $date)
    {
        return (object) [
            'id' => $emp->id,
            'name' => $emp->name,
            'group_name' => $emp->group_name,
            'division' => $emp->division_name_en,
            'district' => $emp->district_name_en,
            'upazila' => $emp->upazila_name_en,
            'date' => $date,
            'in_time' => null,
            'out_time' => null,
            'status_sms' => 'Absent',
            'remark' => 'Did not attend work.',
            'overtime_minutes' => 0,
            'overtime' => '00:00'
        ];
    }

    /**
     * Determine report type
     */
    private function getReportType(array $validated, Carbon $start, Carbon $end): int
    {
        if (!empty($validated['month'])) {
            return 3;  // Month
        } elseif ($start->eq($end)) {
            return 1;  // Single day
        } else {
            return 2;  // Multiple dates
        }
    }
}
