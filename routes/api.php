<?php

use App\Http\Controllers\Api\AccessDBController;  // AccsessDBController
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\AttendanceStatusController;
use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\Api\DatabaseManageController;
use App\Http\Controllers\Api\DateShiftAttendanceController;
use App\Http\Controllers\Api\EmployeeGroupController;
use App\Http\Controllers\Api\FlexibleTimeGroupController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\GroupSpecialWorkdayController;
use App\Http\Controllers\Api\HolidayController;
use App\Http\Controllers\Api\ShiftController;
use App\Http\Controllers\Api\SpecialWorkingdayController;
use App\Http\Controllers\Api\WorkdayController;
use App\Http\Controllers\Api\WorkdayGroupController;

use App\Http\Controllers\Api\EmployeeManage\DivisionApiController ;
use App\Http\Controllers\Api\EmployeeManage\DistrictApiController ;
use App\Http\Controllers\Api\EmployeeManage\UpazilaApiController ;
use App\Http\Controllers\Api\EmployeeManage\DesignationApiController ;
use App\Http\Controllers\Api\EmployeeManage\EmployeeApiController ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use Illuminate\Support\Facades\Log;


// require __DIR__ . '/auth.php';

// Route::middleware('auth:sanctum')->group(function () {

// 🧩 Authenticated user info
Route::get('/user', function (Request $request) {
    return $request->user();
});

// ✅ BRANCH MANAGEMENT
Route::prefix('branch_manage')->group(function () {
    Route::get('/list', [BranchController::class, 'index']);
    Route::post('/store', [BranchController::class, 'store']);
    Route::get('/{uid}', [BranchController::class, 'getByUid']);
    Route::get('edit/{uid}', [BranchController::class, 'edit']);
    Route::put('/update/{uid}', [BranchController::class, 'update']);
    Route::delete('/delete/{id}', [BranchController::class, 'destroy']);
});
// ✅ SHIFT MANAGEMENT
Route::prefix('shift_manage')->group(function () {
    Route::get('/list', [ShiftController::class, 'index']);
    Route::post('/add', [ShiftController::class, 'add']);
    Route::post('/store', [ShiftController::class, 'store']);
    Route::get('/edit/{uid}', [ShiftController::class, 'edit']);
    Route::put('/update/{uid}', [ShiftController::class, 'update']);
    Route::delete('/delete/{id}', [ShiftController::class, 'destroy']);
});
// ✅ Work Day MANAGEMENT
Route::prefix('day_manage')->group(function () {
    Route::get('/list', [WorkdayController::class, 'index']);
    Route::post('/store', [WorkdayController::class, 'store']);
    Route::get('/edit/{id}', [WorkdayController::class, 'edit']);
    Route::put('/update/{id}', [WorkdayController::class, 'update']);
    Route::delete('/delete/{id}', [WorkdayController::class, 'destroy']);
});

// ✅ GROUP MANAGEMENT
Route::prefix('group_manage')->group(function () {
    Route::get('/list', [GroupController::class, 'index']);
    Route::get('/add', [GroupController::class, 'add']);
    Route::get('/details/{id}', [GroupController::class, 'details']);
    Route::post('/store', [GroupController::class, 'store']);
    Route::get('/edit/{id}', [GroupController::class, 'edit']);
    Route::put('/update/{id}', [GroupController::class, 'update']);
    Route::delete('/delete/{group}', [GroupController::class, 'destroy']);
    Route::get('/download-pdf/{id}', [GroupController::class, 'downloadPdf']);
    Route::post('/toggle-status/{id}', [GroupController::class, 'toggleStatus']);

    Route::get('/shifts-by-branch/{branchCode}', function ($branchCode) {
        // Add CORS headers
        // header('Access-Control-Allow-Origin: http://localhost:8000');
        // header('Access-Control-Allow-Methods: GET, OPTIONS');
        // header('Access-Control-Allow-Headers: Content-Type, Authorization');

        $shifts = \App\Models\ShiftSetting::where('branch_code', $branchCode)
            ->where('status', 1)
            ->select('id', 'shift_name_en', 'shift_name_bn')
            ->get();

        return response()->json(['shifts' => $shifts]);
    });
});

// ✅ Employee Group MANAGEMENT
Route::get('groups/{group}/employees/list', [EmployeeGroupController::class, 'index']);
Route::post('groups/{group}/employees', [EmployeeGroupController::class, 'store']);
Route::put('groups/{group}/employees', [EmployeeGroupController::class, 'update']);
Route::post('groups/{group}/employees/delete', [EmployeeGroupController::class, 'destroy']);
Route::delete('groups/{group}/employees', [EmployeeGroupController::class, 'detachAll']);

// ✅ Work Day Group MANAGEMENT
Route::get('groups/{group}/work_day/list', [WorkdayGroupController::class, 'index']);
Route::post('groups/{group}/work_day', [WorkdayGroupController::class, 'store']);
Route::put('groups/{group}/work_day', [WorkdayGroupController::class, 'update']);
Route::delete('groups/{group}/work_day/{work_day}', [WorkdayGroupController::class, 'destroy']);
Route::delete('groups/{group}/work_day', [WorkdayGroupController::class, 'detachAll']);

// ✅ Flexible Time Group MANAGEMENT
Route::prefix('flexible_time_group')->group(function () {
    Route::get('/list', [FlexibleTimeGroupController::class, 'index']);
    Route::post('/store', [FlexibleTimeGroupController::class, 'store']);
    Route::get('/show/{flexibleTimeGroup}', [FlexibleTimeGroupController::class, 'show']);  // ← this is the show route
    Route::get('/edit/{flexibleTimeGroup}', [FlexibleTimeGroupController::class, 'edit']);
    Route::put('/update/{flexibleTimeGroup}', [FlexibleTimeGroupController::class, 'update']);
    Route::delete('/delete/{flexibleTimeGroup}', [FlexibleTimeGroupController::class, 'destroy']);
});

// ✅ Special Work day MANAGEMENT
Route::prefix('special_working_day')->group(function () {
    Route::get('/list', [SpecialWorkingdayController::class, 'index']);
    Route::post('/store', [SpecialWorkingdayController::class, 'store']);
    Route::get('/show/{specialWorkingday}', [SpecialWorkingdayController::class, 'show']);
    Route::get('/edit/{id}', [SpecialWorkingdayController::class, 'edit']);
    Route::put('/update/{specialWorkingday}', [SpecialWorkingdayController::class, 'update']);

    Route::delete('/delete/{specialWorkingday}', [SpecialWorkingdayController::class, 'destroy']);
});

//  Group Special Work day MANAGEMENT
Route::prefix('group_special_workday')->group(function () {
    Route::get('/list/{group}', [GroupSpecialWorkdayController::class, 'list']);
    Route::post('/assign/{group}', [GroupSpecialWorkdayController::class, 'assign']);
    Route::put('/assign/{group}', [GroupSpecialWorkdayController::class, 'update']);
    Route::delete('/detach/{group}/{special_workday_id}', [GroupSpecialWorkdayController::class, 'detach']);
});

// ✅ =========== Database Management ==========

Route::get('set_db_location', fn() =>
    response()->json(
        [
            'success' => true,
            'message' => 'Choose the DB Location & Time Schedule to Sync',
        ], 200
    ));
// Route::post('update_time_schedule', function(){

//     Log::info('hi1');
//     return response()->json(
//         [
//             'success' => true,
//             'message' => 'fuck',
//         ], 200
//     );
// });

   
Route::post('update_time_schedule', [DatabaseManageController::class, 'updateTimeSchedule']);

// ✅ ATTENDANCE STATUS MANAGEMENT
Route::prefix('attendance_status')->group(function () {
    Route::get('/', [AttendanceStatusController::class, 'index']);  // GET all
    Route::post('/store', [AttendanceStatusController::class, 'store']);  // POST new
    Route::get('show/{id}', [AttendanceStatusController::class, 'show']);  // GET single
    Route::put('update/{id}', [AttendanceStatusController::class, 'update']);  // PUT update
    Route::delete('delete/{attendance_status}', [AttendanceStatusController::class, 'destroy']);  // DELETE
});
// ✅ HOLIDAY MANAGEMENT
Route::prefix('holiday_manage')->group(function () {
    Route::get('/list', [HolidayController::class, 'index']);  // GET all
    Route::post('/store', [HolidayController::class, 'store']);  // POST new
    Route::get('show/{id}', [HolidayController::class, 'show']);  // GET single
    Route::put('update/{id}', [HolidayController::class, 'update']);  // PUT update
    Route::delete('delete/{attendance_status}', [HolidayController::class, 'destroy']);  // DELETE
});

// ✅ Access DB Controll 👈
Route::post('/accessBdStore', [AccessDBController::class, 'accessDBstore']);

// ✅✅ Index page View -Controller ( Branch,shift,dates ...etc) 🧪🧪

Route::get('/attendance/show', [DateShiftAttendanceController::class, 'index']);
// Route::get('/shifts/by-branch/{branchId}', [DateShiftAttendanceController::class, 'getShiftsByBranch']);
// Route::get('/districts/by-division/{divisionId}', [DateShiftAttendanceController::class, 'getDistrictsByDivision']);
// Route::get('/upazilas/by-district/{districtId}', [DateShiftAttendanceController::class, 'getUpazilasByDistrict']);


// ✅✅ Employee Attendance Calculation 🔥🔥🔥
Route::post('/branch/shift/date/attendance', [AttendanceController::class, 'index']);



// Route::get('/get-departments', [DateShiftAttendanceController::class, 'getDepartments']);
// Route::get('/get-sections', [DateShiftAttendanceController::class, 'getSections']);


 // EMPLOYEE MANAGEMENT API
 // ✅ DIVISION MANAGEMENT API
Route::prefix('division_manage')->group(function () {
    Route::get('/list', [DivisionApiController::class, 'index'])->name('api.division.index');
    Route::post('/store', [DivisionApiController::class, 'store'])->name('api.division.store');
    Route::get('/show/{id}', [DivisionApiController::class, 'show'])->name('api.division.show');
    Route::put('/edit/{id}', [DivisionApiController::class, 'update'])->name('api.division.update');
    Route::delete('/delete/{id}', [DivisionApiController::class, 'destroy'])->name('api.division.destroy');
});


// ✅ DISTRICT MANAGEMENT API
Route::prefix('district_manage')->group(function () {
    Route::get('/list', [DistrictApiController::class, 'index'])->name('api.districts.index');
    Route::post('/store', [DistrictApiController::class, 'store'])->name('api.districts.store');
    Route::get('/show/{id}', [DistrictApiController::class, 'show'])->name('api.districts.show');
    Route::put('/update/{id}', [DistrictApiController::class, 'update'])->name('api.districts.update');
    Route::delete('/delete/{id}', [DistrictApiController::class, 'destroy'])->name('api.districts.destroy');
    
    // Additional route for creating form data (divisions list)
    Route::get('/create/data', [DistrictApiController::class, 'createData'])->name('api.districts.create.data');
});

// ✅ UPAZILA MANAGEMENT API
Route::prefix('upazila_manage')->group(function () {
    Route::get('/list', [UpazilaApiController::class, 'index'])->name('api.upazilas.index');
    Route::post('/store', [UpazilaApiController::class, 'store'])->name('api.upazilas.store');
    Route::get('/show/{id}', [UpazilaApiController::class, 'show'])->name('api.upazilas.show');
    Route::put('/update/{id}', [UpazilaApiController::class, 'update'])->name('api.upazilas.update');
    Route::delete('/delete/{id}', [UpazilaApiController::class, 'destroy'])->name('api.upazilas.destroy');
    
    // Additional route for creating form data (districts list)
    Route::get('/create/data', [UpazilaApiController::class, 'createData'])->name('api.upazilas.create.data');
});


// ✅ DESIGNATION MANAGEMENT API

Route::prefix('designation_manage')->group(function () {
    Route::get('/list', [DesignationApiController::class, 'index'])->name('api.designation.index');
    Route::post('/store', [DesignationApiController::class, 'store'])->name('api.designation.store');
    Route::get('/show/{id}', [DesignationApiController::class, 'show'])->name('api.designation.show');
    Route::put('/update/{id}', [DesignationApiController::class, 'update'])->name('api.designation.update');
    Route::delete('/delete/{id}', [DesignationApiController::class, 'destroy'])->name('api.designation.destroy');
});


// 👨‍💻 EMPLOYEE MANAGEMENT API (Teacher + Staff + Student) 👈

Route::prefix('employee_manage')->group(function () {
    Route::get('/list', [EmployeeApiController::class, 'index'])->name('api.employee.index');
    Route::post('/store', [EmployeeApiController::class, 'store'])->name('api.employee.store');
    Route::get('/show/{id}', [EmployeeApiController::class, 'show'])->name('api.employee.show');
    Route::put('/update/{id}', [EmployeeApiController::class, 'update'])->name('api.employee.update');
    Route::delete('/delete/{id}', [EmployeeApiController::class, 'destroy'])->name('api.employee.destroy');
});


