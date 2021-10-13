<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\LeavesController;
use App\Http\Controllers\AttendanceController;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('register_organization', [OrganizationController::class, 'store']);

Route::group(['middleware' => ['jwt.verify']], function () {
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('leave_request', [LeavesController::class, 'store']);
    Route::get('register_attendance', [AttendanceController::class, 'register']);
    Route::get('finalize_attendance', [AttendanceController::class, 'finalize']);
    Route::get('user_profile', [AuthController::class, 'userProfile']);
    Route::group(['middleware' => ['admin']], function () {
        Route::get('approve_attendance/{id}', [AttendanceController::class, 'approveByManager']);
        Route::get('approve_attendance_hr/{id}', [AttendanceController::class, 'approveByHR']);
        Route::post('add_department', [OrganizationController::class, 'addDepartment']);
    });
});
