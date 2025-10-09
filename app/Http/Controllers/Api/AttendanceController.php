<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AttendanceStatus;
use App\Models\Employee;
use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
  public function index(Request $request)
  {
    // Validate inputs
    $validated = $request->validate([
      'shift_id' => 'required|integer|exists:shift_settings,id',
      'date_range' => 'nullable|string|required_without:month',
      'month' => 'nullable|date_format:Y-m|required_without:date_range',
      'upazila_id' => 'nullable|integer|exists:upazilas,id',
      'district_id' => 'nullable|integer|exists:districts,id',
      'division_id' => 'nullable|integer|exists:divisions,id',
      'group_id' => 'nullable|integer|exists:groups,id',
    ]);

    // Parse date range or month
    [$start, $end] = $this->getDateRangeFromRequest($validated);

    // Get shift name
    $shiftName = DB::table('shift_settings')
      ->where('id', $validated['shift_id'])
      ->value('shift_name');

    // Get employees filtered by request
    $employees = $this->getFilteredEmployees($validated);

    // Get attendance records for date range
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
      $shiftName,
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
   * Get employees with filters
   */
  private function getFilteredEmployees(array $validated)
  {
    $query = Employee::join('employee_group', 'employee_group.employee_id', '=', 'employees.id')
      ->join('groups', function ($join) use ($validated) {
        $join
          ->on('groups.id', '=', 'employee_group.group_id')
          ->where('groups.shift_id', '=', $validated['shift_id']);
      })
      ->join('shift_settings', 'shift_settings.id', '=', 'groups.shift_id')
      ->leftJoin('divisions', 'divisions.id', '=', 'employees.division_id')
      ->leftJoin('districts', 'districts.id', '=', 'employees.district_id')
      ->leftJoin('upazilas', 'upazilas.id', '=', 'employees.upazila_id')
      ->select(
        'employees.id',
        'employees.profile_id',
        'employees.name',
        'groups.group_name',
        'shift_settings.start_time',
        'shift_settings.end_time',
        'groups.flexible_in_time',
        'groups.flexible_out_time',
        'divisions.name as division_name_en',
        'districts.name as district_name_en',
        'upazilas.name as upazila_name_en',
        'divisions.id as division_id',
        'districts.id as district_id',
        'upazilas.id as upazila_id'
      );

    if (!empty($validated['upazila_id'])) {
      $query->where('employees.upazila_id', $validated['upazila_id']);
    } elseif (!empty($validated['district_id'])) {
      $query->where('employees.district_id', $validated['district_id']);
    } elseif (!empty($validated['division_id'])) {
      $query->where('employees.division_id', $validated['division_id']);
    }

    if (!empty($validated['group_id'])) {
      $query->where('employee_group.group_id', $validated['group_id']);
    }

    return $query->get();
  }

  /**
   * Get attendance data for users within a date range
   */
  private function getAttendanceData(array $profileIds, Carbon $start, Carbon $end)
  {
    return DB::table('check_in_outs')
      ->whereIn('user_id', $profileIds)
      ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
      ->get()
      ->groupBy(fn($item) => $item->user_id . '_' . $item->date);
  }

  /**
   * Build final attendance results
   */
  private function buildAttendanceResults($employees, $attendanceData, Carbon $start, Carbon $end, string $shiftName, int $shiftId)
  {
    $results = [];

    for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
      $currentDate = $date->toDateString();
      $dayName = $date->format('l');

      // Determine if it's a working day
      $workdayInfo = $this->checkWorkday($shiftId, $dayName, $currentDate);
      if ($workdayInfo['status'] !== 'Working Day') {
        $results[] = array_merge([
          'date' => $currentDate,
          'shift_name' => $shiftName,
          'attendance' => []
        ], $workdayInfo);
        continue;
      }

      // Build daily attendance
      $dailyAttendance = $this->buildDailyAttendance($employees, $attendanceData, $currentDate);

      $results[] = [
        'date' => $currentDate,
        'shift_name' => $shiftName,
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
  private function checkWorkday(int $shiftId, string $dayName, string $currentDate): array
  {
    $workDay = DB::table('work_days')->where('day_name', $dayName)->first();
    if (!$workDay) {
      return [
        'status' => 'Invalid day name',
        'holiday_name' => null,
        'description' => "$dayName not defined in work_days table"
      ];
    }

    $weekdayId = $workDay->id;
    $employeeGroups = DB::table('employee_group')
      ->join('groups', 'groups.id', '=', 'employee_group.group_id')
      ->where('groups.shift_id', $shiftId)
      ->pluck('group_id');

    $groupWorkDays = DB::table('work_day_group')
      ->whereIn('group_id', $employeeGroups)
      ->pluck('work_day_id', 'group_id')
      ->toArray();

    $weekdayIsWorkingDay = in_array($weekdayId, $groupWorkDays);

    $specialWorkingDates = DB::table('group_special_workdays')
      ->join('special_working_days', 'special_working_days.id', '=', 'group_special_workdays.special_workingday_id')
      ->whereIn('group_special_workdays.group_id', $employeeGroups)
      ->whereDate('special_working_days.date', $currentDate)
      ->exists();

    if ($specialWorkingDates) {
      $weekdayIsWorkingDay = true;
    }

    if (!$weekdayIsWorkingDay) {
      return [
        'status' => 'Weekend Holiday',
        'holiday_name' => 'Weekly Off',
        'description' => "$dayName is a non-working day"
      ];
    }

    // Holiday check
    $isHoliday = Holiday::where('status', 1)
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

  /** Build daily attendance records */

  /**
   * Build daily attendance records for all employees for a given date
   */
  private function buildDailyAttendance($employees, $attendanceData, string $currentDate)
  {
    $dailyAttendance = $employees->map(function ($emp) use ($attendanceData, $currentDate) {
      $key = $emp->profile_id . '_' . $currentDate;
      $record = $attendanceData[$key][0] ?? null;

      if (!$record || empty($record->in_time) || $record->in_time === '00:00' || empty($record->out_time) || $record->out_time === '00:00') {
        return $this->absentRecord($emp, $currentDate);
      }

      return $this->presentRecord($emp, $record, $currentDate);
    });

    // Convert to plain array
    return $dailyAttendance->values()->all();
  }

  private function absentRecord($emp, $date)
  {
    return (object) [
      'id' => $emp->id,
      'group_name' => $emp->group_name,
      'division' => $emp->division_name_en,
      'department' => $emp->district_name_en,
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

  private function presentRecord($emp, $record, $date)
  {
    $startTime = Carbon::parse($date . ' ' . $emp->start_time);
    $endTime = Carbon::parse($date . ' ' . $emp->end_time);
    $inTime = Carbon::parse($date . ' ' . $record->in_time);
    $outTime = Carbon::parse($date . ' ' . $record->out_time);

    $flexIn = (int) $emp->flexible_in_time;
    $flexOut = (int) $emp->flexible_out_time;

    $remarks = [];
    $statusFlags = [];

    if ($inTime->gt($startTime)) {
      $lateMinutes = $startTime->diffInMinutes($inTime, false);
      if ($lateMinutes > $flexIn) {
        $actualLate = $lateMinutes - $flexIn;
        $remarks[] = "Late by {$actualLate} minutes.";
        $statusFlags[] = 'Late';
      }
    }

    if ($outTime->lt($endTime)) {
      $earlyMinutes = $outTime->diffInMinutes($endTime, false);
      if ($earlyMinutes > $flexOut) {
        $actualEarly = $earlyMinutes - $flexOut;
        $remarks[] = "Left early by {$actualEarly} minutes.";
        $statusFlags[] = 'Left Early';
      }
    }

    $overtimeMinutes = 0;
    $overtime = '00:00';
    if ($outTime->gt($endTime)) {
      $overtimeMinutes = $endTime->diffInMinutes($outTime);
      $overtime = sprintf('%02d:%02d', floor($overtimeMinutes / 60), $overtimeMinutes % 60);
      $remarks[] = "Worked overtime of {$overtime} hours.";
    }

    $status_sms = empty($statusFlags) ? 'Present' : implode(' & ', $statusFlags);
    if (empty($remarks))
      $remarks[] = 'Present for full shift.';

    return (object) [
      'id' => $emp->id,
      'group_name' => $emp->group_name,
      'division' => $emp->division_name_en,
      'department' => $emp->district_name_en,
      'upazila' => $emp->upazila_name_en,
      'date' => $date,
      'in_time' => $record->in_time,
      'out_time' => $record->out_time,
      'start_time' => $emp->start_time,
      'end_time' => $emp->end_time,
      'flexible_in_time' => $emp->flexible_in_time,
      'flexible_out_time' => $emp->flexible_out_time,
      'status_sms' => $status_sms,
      'remark' => implode(' ', $remarks),
      'overtime_minutes' => $overtimeMinutes,
      'overtime' => $overtime
    ];
  }
}
