<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\LeavesController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\FleetRequestController;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('register_organization', [OrganizationController::class, 'store']);

Route::group(['middleware' => ['jwt.verify']], function () {
    Route::post('register_for_notifications', [NotificationsController::class, 'registerToken']);
    Route::get('user_profile', [AuthController::class, 'userProfile']);
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

    Route::post('fleet_request', [FleetRequestController::class, 'fleetRequest']);
    Route::post('add_destination/{id}', [FleetRequestController::class, 'addDestination']);
    Route::post('leave_request', [LeavesController::class, 'request']);

    Route::get('register_attendance', [AttendanceController::class, 'register']);
    Route::get('finalize_attendance', [AttendanceController::class, 'finalize']);
    Route::get('get_attendance_record', [AttendanceController::class, 'getAttendanceRecord']);

    Route::group(['middleware' => ['admin']], function () {
        /* *********** routes for attendance controller: admin APIs *********** */
        Route::get('approve_attendance/{id}', [AttendanceController::class, 'approveByManager']);
        Route::get('reject_attendance/{id}', [AttendanceController::class, 'rejectByManager']);
        Route::get('attendance_records', [AttendanceController::class, 'getAttendanceRecordPerUser']);
        Route::get('approve_attendance_hr/{id}', [AttendanceController::class, 'approveByHR']);

        /* *********** routes for leaves controller: admin APIs *********** */
        Route::get('approve_leave/{id}', [LeavesController::class, 'approveByManager']);
        Route::get('reject_leave/{id}', [LeavesController::class, 'rejectByManager']);
        Route::get('leaves_records', [LeavesController::class, 'getLeavesRecordPerUser']);
        Route::get('approve_leave_hr/{id}', [LeavesController::class, 'approveByHR']);

        /* *********** routes for vehicles and fuel controller: admin APIs *********** */
        Route::post('add_department', [OrganizationController::class, 'addDepartment']);
        Route::post('add_vehicle', [VehicleController::class, 'addVehicle']);
        Route::post('view_vehicles', [VehicleController::class, 'viewVehiclesInfo']);
    });
});
