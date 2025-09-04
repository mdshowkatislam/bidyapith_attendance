<?php

use App\Http\Controllers\Admin\Frontend\BaseGroupController;
use App\Http\Controllers\Admin\Frontend\BaseHolidayController;
use App\Http\Controllers\Admin\Frontend\BaseBranchController;
use App\Http\Controllers\Admin\Frontend\BaseShiftController;
use App\Http\Controllers\Admin\Frontend\BaseSpecialWorkingdayController;
use App\Http\Controllers\Admin\Frontend\BaseWorkdayController;
// use App\Http\Controllers\Admin\Frontend\WebAccessDBController;
// use App\Http\Controllers\Admin\Frontend\WebDatabaseManageController;
use App\Http\Controllers\Admin\Frontend\BaseDateShiftAttendanceController;
use App\Http\Controllers\Admin\EmployeeProfileController;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    // ✅ SHIFT MANAGEMENT
    Route::prefix('branch')->group(function () {
        Route::get('/', [BaseBranchController::class, 'index'])->name('branch.index');
        Route::get('/create', function () {
            return view('admin.frontend.branch.create');
        })->name('branch.create');
        Route::post('/store', [BaseBranchController::class, 'store'])->name('branch.store');
        Route::get('/edit/{id}', [BaseBranchController::class, 'edit'])->name('branch.edit');
        Route::post('/update/{id}', [BaseBranchController::class, 'update'])->name('branch.update');
        Route::get('/delete/{id}', [BaseBranchController::class, 'destroy'])->name('branch.destroy');
    });
    // ✅ SHIFT MANAGEMENT
    Route::prefix('shift')->group(function () {
        Route::get('/', [BaseShiftController::class, 'index'])->name('shift.index');
        Route::get('/create', function () {
            return view('admin.frontend.shift.create');
        })->name('shift.create');
        Route::post('/store', [BaseShiftController::class, 'store'])->name('shift.store');
        Route::get('/edit/{id}', [BaseShiftController::class, 'edit'])->name('shift.edit');
        Route::post('/update/{id}', [BaseShiftController::class, 'update'])->name('shift.update');
        Route::get('/delete/{id}', [BaseShiftController::class, 'destroy'])->name('shift.destroy');
    });
    // ✅ HOLIDAY MANAGEMENT
    Route::prefix('holiday')->group(function () {
        Route::get('/', [BaseHolidayController::class, 'index'])->name('holiday.index');
        Route::get('/create', function () {
            return view('admin.frontend.holiday.form');
        })->name('holiday.create');
        Route::post('/store', [BaseHolidayController::class, 'store'])->name('holiday.store');
        Route::get('edit/{id}', [BaseHolidayController::class, 'edit'])->name('holiday.edit');
        Route::put('update/{id}', [BaseHolidayController::class, 'update'])->name('holiday.update');
        Route::delete('delete/{attendance_status}', [BaseHolidayController::class, 'destroy'])->name('holiday.destroy');
    });
    // ✅ Work Day MANAGEMENT
    Route::prefix('work_day')->group(function () {
        Route::get('/', [BaseWorkdayController::class, 'index'])->name('work_day.create');
        Route::get('/create', function () {
            return view('admin.frontend.work_day.form');
        })->name('work_day.create');
        Route::post('/store', [BaseWorkdayController::class, 'store'])->name('work_day.store');
        Route::get('/edit/{id}', [BaseWorkdayController::class, 'edit'])->name('work_day.edit');
        Route::put('/update/{id}', [BaseWorkdayController::class, 'update'])->name('work_day.update');
        Route::delete('/delete/{id}', [BaseWorkdayController::class, 'destroy'])->name('work_day.destroy');
    });

    // ✅ Special Work day MANAGEMENT

    Route::get('special_working_day/', [BaseSpecialWorkingdayController::class, 'index'])->name('special_working_day.index');

    Route::get('/special_working_day/create', [BaseSpecialWorkingdayController::class, 'add'])->name('special_working_day.create');
    Route::get('special_working_day/edit/{id}', [BaseSpecialWorkingdayController::class, 'edit'])->name('special_working_day.edit');
    Route::delete('special_working_day/delete/{id}', [BaseSpecialWorkingdayController::class, 'destroy'])->name('special_working_day.destroy');

    // ✅ GROUP MANAGEMENT
    Route::prefix('group_manage')->group(function () {
        Route::get('/list', [BaseGroupController::class, 'index'])->name('group_manage.index');
        Route::get('/create', [BaseGroupController::class, 'add'])->name('group_manage.create');

        Route::get('/details/{id}', [BaseGroupController::class, 'previewPdfView'])->name('group_manage.pdf');

        Route::get('/download/pdf/{id}', [BaseGroupController::class, 'downloadGroupPdf'])->name('group_manage.download.pdf');

        Route::post('/store', [BaseGroupController::class, 'store'])->name('group_manage.store');
        Route::get('/edit/{id}', [BaseGroupController::class, 'edit'])->name('group_manage.edit');
        // Route::put('/update/{id}', [BaseGroupController::class, 'update'])->name('group_manage.update');
        Route::delete('/delete/{id}', [BaseGroupController::class, 'destroy'])->name('group_manage.delete');
    });

    // ✅ ERROR PAGE HANDLER
    Route::get('/error-page', function () {
        return view('errors.error');
    })->name('error-page');

    // ✅ =========== Database Management ========== 


    Route::get('/web/set_db_location', function () {
        return view('admin.db.db_location');
    })->name('set_db_location');

// ✅ =========== Attendance Calculation ========== 

    Route::get('/date-shift-wise-attendance', [BaseDateShiftAttendanceController::class, 'index'])->name('date_shift_wise_attendance');

    Route::get('attendance-report', [BaseDateShiftAttendanceController::class, 'reportGenarate'])->name('attendance.report');

    Route::get('/date/{date}/shift/{shiftId}/attendance-report', [BaseDateShiftAttendanceController::class, 'attendancePdf'])->name('attendance-report.pdf');
});
