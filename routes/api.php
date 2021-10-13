<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\LeavesController;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('register_organization', [OrganizationController::class, 'store']);

Route::group(['middleware' => ['jwt.verify']], function () {
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('leave_request', [LeavesController::class, 'store']);
    Route::post('register_attendance', [AttendanceController::class, 'store']);
    Route::group(['middleware' => ['admin']], function () {
        Route::get('user_profile', [AuthController::class, 'userProfile']);
    });
});
