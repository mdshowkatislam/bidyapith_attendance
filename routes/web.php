<?php

use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\DivisionController;
use App\Http\Controllers\Admin\EmployeeProfileController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ✅ DIVISION MANAGEMENT
    Route::prefix('division')->group(function () {
        Route::get('/list', [DivisionController::class, 'index'])->name('division.list');
        Route::post('/store', [DivisionController::class, 'store'])->name('division.store');
        Route::get('add/{id}', [DivisionController::class, 'add'])->name('division.add');
        Route::get('edit/{id}', [DivisionController::class, 'edit'])->name('division.edit');
        Route::put('update/{id}', [DivisionController::class, 'update'])->name('division.update');
        Route::get('delete/{id}', [DivisionController::class, 'destroy'])->name('division.delete');
    });
    // ✅ DEPARTMENT MANAGEMENT
    Route::prefix('department')->group(function () {
        Route::get('/list', [DepartmentController::class, 'index'])->name('department.list');
        Route::post('/store', [DepartmentController::class, 'store'])->name('department.store');
        Route::get('add/{id}', [DepartmentController::class, 'add'])->name('department.add');
        Route::get('edit/{id}', [DepartmentController::class, 'edit'])->name('department.edit');
        Route::put('update/{id}', [DepartmentController::class, 'update'])->name('department.update');
        Route::delete('delete/{attendance_status}', [DepartmentController::class, 'destroy'])->name('department.delete');
    });
    // ✅ SECTION MANAGEMENT
    Route::prefix('section')->group(function () {
        Route::get('/list', [SectionController::class, 'index'])->name('section.list');
        Route::post('/store', [SectionController::class, 'store'])->name('section.store');
        Route::get('add/{id}', [SectionController::class, 'add'])->name('section.add');
        Route::get('edit/{id}', [SectionController::class, 'edit'])->name('section.edit');
        Route::put('update/{id}', [SectionController::class, 'update'])->name('section.update');
        Route::delete('delete/{attendance_status}', [SectionController::class, 'destroy'])->name('section.delete');
    });
    // ✅ EMPLOYEE PROFILE MANAGEMENT
    Route::prefix('employee_profile')->group(function () {
        Route::get('/list', [EmployeeProfileController::class, 'index'])->name('employee_profile.index');
        Route::post('/store', [EmployeeProfileController::class, 'store'])->name('employee_profile.store');
        Route::get('/add', [EmployeeProfileController::class, 'add'])->name('employee_profile.add');
        Route::get('edit/{id}', [EmployeeProfileController::class, 'edit'])->name('employee_profile.edit');
        Route::put('update/{id}', [EmployeeProfileController::class, 'update'])->name('employee_profile.update');
        Route::delete('delete/{attendance_status}', [EmployeeProfileController::class, 'destroy'])->name('employee_profile.destroy');;

        Route::get('/get-departments', [EmployeeProfileController::class, 'getDepartments'])->name('get.departments');
        Route::get('/get-sections', [EmployeeProfileController::class, 'getSections'])->name('get.sections');
        Route::patch('employee-profile/{id}/toggle-status', [EmployeeProfileController::class, 'toggleStatus'])->name('employee_profile.toggleStatus');

    });
});

require __DIR__ . '/self.php';
require __DIR__ . '/auth.php';
