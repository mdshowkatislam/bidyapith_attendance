<?php

use App\Http\Controllers\Admin\DistrictController;
use App\Http\Controllers\Admin\DivisionController;
use App\Http\Controllers\Admin\EmployeeProfileController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UpazilaController;
use Illuminate\Support\Facades\Route;
// use Illuminate\Support\Facades\Log;

// Route::get('/log', function () {
//     Log::debug('This is a debug log test');
//     return 'Log written!';
// });

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
    // ✅ DISTRICT MANAGEMENT
    Route::prefix('district')->group(function () {
        Route::get('/list', [DistrictController::class, 'index'])->name('district.list');
        Route::post('/store', [DistrictController::class, 'store'])->name('district.store');
        Route::get('add/{id}', [DistrictController::class, 'add'])->name('district.add');
        Route::get('edit/{id}', [DistrictController::class, 'edit'])->name('district.edit');
        Route::put('update/{id}', [DistrictController::class, 'update'])->name('district.update');
        Route::delete('delete/{attendance_status}', [DistrictController::class, 'destroy'])->name('district.delete');
    });
    // ✅ Upazila MANAGEMENT
    Route::prefix('upazila')->group(function () {
        Route::get('/list', [UpazilaController::class, 'index'])->name('upazila.list');
        Route::post('/store', [UpazilaController::class, 'store'])->name('upazila.store');
        Route::get('add/{id}', [UpazilaController::class, 'add'])->name('upazila.add');
        Route::get('edit/{id}', [UpazilaController::class, 'edit'])->name('upazila.edit');
        Route::put('update/{id}', [UpazilaController::class, 'update'])->name('upazila.update');
        Route::delete('delete/{attendance_status}', [UpazilaController::class, 'destroy'])->name('upazila.delete');
    });
    // ✅ EMPLOYEE PROFILE MANAGEMENT
    Route::prefix('employee_profile')->group(function () {
        Route::get('/list', [EmployeeProfileController::class, 'index'])->name('employee_profile.index');
        Route::post('/store', [EmployeeProfileController::class, 'store'])->name('employee_profile.store');
        Route::get('/add', [EmployeeProfileController::class, 'add'])->name('employee_profile.add');
        Route::get('edit/{id}', [EmployeeProfileController::class, 'edit'])->name('employee_profile.edit');
        Route::put('update/{id}', [EmployeeProfileController::class, 'update'])->name('employee_profile.update');
        Route::delete('delete/{attendance_status}', [EmployeeProfileController::class, 'destroy'])->name('employee_profile.destroy');
        Route::get('/get-districts/{divisionId}', [EmployeeProfileController::class, 'getDistrictsByDivision'])->name('get.districts');
        Route::get('/get-upazilas/{districtId}', [EmployeeProfileController::class, 'getUpazilasByDistrict'])->name('get.upazilas');
        Route::patch('employee-profile/{id}/toggle-status', [EmployeeProfileController::class, 'toggleStatus'])->name('employee_profile.toggleStatus');
    });
});

require __DIR__ . '/self.php';
require __DIR__ . '/auth.php';



