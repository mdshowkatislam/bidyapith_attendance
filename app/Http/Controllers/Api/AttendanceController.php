<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GroupResource;
use App\Models\Employee;
use App\Models\Group;
use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('api_url.baseUrl_1'), '/');
    }

    public function index(Request $request)
    {
        // Validate only local fields
        $validated = $request->validate([
            'branch_id' => 'required|integer',
            'shift_id' => 'required|integer',
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
        $shift = $this->fetchShiftDetails($validated['shift_id']);
        if (!$shift) {
            return response()->json(['error' => 'Shift not found'], 404);
        }

        // Get branch details from external service
        $branch = $this->fetchBranchDetails($validated['branch_id']);
        if (!$branch) {
            return response()->json(['error' => 'Branch not found'], 404);
        }

        // Get employees filtered by branch, shift and other criteria
        $employees = $this->getFilteredEmployees($validated, $branch, $shift);

        // Get attendance records for date range from external service
        $attendanceData = $this->getAttendanceData(
            $employees->pluck('profile_id')->toArray(),
            $start,
            $end
        );

        // Build results for each date
        $results = $this->buildAttendanceResults(
            $employees,
            $attendanceData,
            $start,
            $end,
            $shift,
            $validated['branch_id'],
            $validated['shift_id']
        );

        // Determine type
        if (!empty($validated['month'])) {
            $type = 3;  // Month
        } elseif ($start->eq($end)) {
            $type = 1;  // Single day
        } else {
            $type = 2;  // Multiple dates
        }

        return response()->json([
            'type' => $type,
            'branch' => $branch,
            'shift' => $shift,
            'results' => $results,
        ]);
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

    /**
     * Fetch shift details from external service
     */
    private function fetchShiftDetails(int $shiftId)
    {
        $url = "{$this->baseUrl}/api/v3/shift/{$shiftId}";
        Log::info("Fetching shift from URL: {$url}");

        try {
            $response = Http::timeout(10)->get($url);
            
            if ($response->successful()) {
                $data = $response->json('data') ?? [];
                Log::info("Shift API Success", ['data' => $data]);
                return is_array($data) ? $data : null;
            } else {
                Log::warning('Shift API failed', [
                    'url' => $url,
                    'status' => $response->status(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('Error fetching shift', [
                'url' => $url, 
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }

    /**
     * Fetch branch details from external service
     */
    private function fetchBranchDetails(int $branchId)
    {
        $url = "{$this->baseUrl}/api/v3/branch/{$branchId}";
        Log::info("Fetching branch from URL: {$url}");

        try {
            $response = Http::timeout(10)->get($url);
            
            if ($response->successful()) {
                $data = $response->json('data') ?? [];
                Log::info("Branch API Success", ['data' => $data]);
                return is_array($data) ? $data : null;
            } else {
                Log::warning('Branch API failed', [
                    'url' => $url,
                    'status' => $response->status(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('Error fetching branch', [
                'url' => $url, 
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }

    /**
     * Get employees filtered by branch, shift and other criteria
     */
    private function getFilteredEmployees(array $validated, array $branch, array $shift)
    {
        // Get groups for the selected branch and shift
        $query = Group::where('branch_uid', $validated['branch_id'])
                     ->where('shift_uid', $validated['shift_id']);

        // If specific group is selected, filter by that group only
        if (!empty($validated['group_id'])) {
            $query->where('id', $validated['group_id']);
        }

        $groups = $query->get();
        $groupIds = $groups->pluck('id')->toArray();

        if (empty($groupIds)) {
            return collect();
        }

        // Base query for employees
        $employeeQuery = Employee::whereHas('groups', function ($query) use ($groupIds) {
            $query->whereIn('groups.id', $groupIds);
        });

        // Apply location filters - these will be validated against external API data
        if (!empty($validated['upazila_id'])) {
            $employeeQuery->where('upazila_id', $validated['upazila_id']);
        } elseif (!empty($validated['district_id'])) {
            $employeeQuery->where('district_id', $validated['district_id']);
        } elseif (!empty($validated['division_id'])) {
            $employeeQuery->where('division_id', $validated['division_id']);
        }

        $employees = $employeeQuery->get();

        // Enrich employee data with external API details
        return $employees->map(function ($employee) use ($groups) {
            $employeeGroup = $employee->groups->first();
            
            // Fetch employee details from external API
            $employeeData = $this->fetchEmployeeDetails(
                $employee->person_type, 
                $employee->profile_id
            );

            // Fetch location names
            $divisionName = isset($employeeData['division_id']) ? 
                $this->fetchDivisionName($employeeData['division_id']) : null;
            $districtName = isset($employeeData['district_id']) ? 
                $this->fetchDistrictName($employeeData['district_id']) : null;
            $upazilaName = isset($employeeData['upazilla_id']) ? 
                $this->fetchUpazilaName($employeeData['upazilla_id']) : null;

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
                'division_name_en' => $divisionName,
                'district_name_en' => $districtName,
                'upazila_name_en' => $upazilaName,
                'division_id' => $employeeData['division_id'] ?? null,
                'district_id' => $employeeData['district_id'] ?? null,
                'upazila_id' => $employeeData['upazilla_id'] ?? null,
            ];
        });
    }

    /**
     * Fetch employee details from external service
     */
    private function fetchEmployeeDetails($personType, $profileId)
    {
        $endpoint = match ($personType) {
            1 => 'teacherAsEmp',
            2 => 'staffAsEmp',
            3 => 'studentAsEmp',
            default => null,
        };

        if (!$endpoint) return [];

        $url = "{$this->baseUrl}/api/v3/{$endpoint}/{$profileId}";
        Log::info("Fetching employee from URL: {$url}");

        try {
            $response = Http::timeout(10)->get($url);
            
            if ($response->successful()) {
                $data = $response->json('data') ?? [];
                Log::info("Employee API Success - Profile: {$profileId}");
                return $data;
            } else {
                Log::warning('Employee API failed', [
                    'url' => $url,
                    'status' => $response->status(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('Employee API exception', [
                'url' => $url, 
                'error' => $e->getMessage(),
            ]);
        }

        return [];
    }

    /**
     * Fetch division name from external service
     */
    private function fetchDivisionName($divisionId)
    {
        $url = "{$this->baseUrl}/api/v3/division/{$divisionId}";
        try {
            $response = Http::timeout(6)->get($url);
            if ($response->successful()) {
                $data = $response->json('data');
                return $data['division_name_en'] ?? $data['division_name'] ?? $divisionId;
            }
        } catch (\Throwable $e) {
            Log::error('Error fetching division', ['url' => $url, 'error' => $e->getMessage()]);
        }
        return $divisionId;
    }

    /**
     * Fetch district name from external service
     */
    private function fetchDistrictName($districtId)
    {
        $url = "{$this->baseUrl}/api/v3/district/{$districtId}";
        try {
            $response = Http::timeout(6)->get($url);
            if ($response->successful()) {
                $data = $response->json('data');
                return $data['district_name_en'] ?? $data['district_name'] ?? $districtId;
            }
        } catch (\Throwable $e) {
            Log::error('Error fetching district', ['url' => $url, 'error' => $e->getMessage()]);
        }
        return $districtId;
    }

    /**
     * Fetch upazila name from external service
     */
    private function fetchUpazilaName($upazilaId)
    {
        $url = "{$this->baseUrl}/api/v3/upazila/{$upazilaId}";
        try {
            $response = Http::timeout(6)->get($url);
            if ($response->successful()) {
                $data = $response->json('data');
                return $data['upazila_name_en'] ?? $data['upazila_name'] ?? $upazilaId;
            }
        } catch (\Throwable $e) {
            Log::error('Error fetching upazila', ['url' => $url, 'error' => $e->getMessage()]);
        }
        return $upazilaId;
    }

    /**
     * Get attendance data from external service
     */
    private function getAttendanceData(array $profileIds, Carbon $start, Carbon $end)
    {
        // This would need to be implemented based on your external attendance service
        // For now, returning empty collection as placeholder
        Log::info("Fetching attendance data for profiles: " . implode(', ', $profileIds));
        Log::info("Date range: {$start->toDateString()} to {$end->toDateString()}");

        // Placeholder - implement based on your external attendance API
        return collect();
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
                'description' => "No groups configured for this branch and shift"
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
            ->where('branch_id', $branchId)
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
}