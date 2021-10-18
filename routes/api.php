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
use App\Http\Controllers\InspectionController;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('register_organization', [OrganizationController::class, 'store']);

Route::group(['middleware' => ['jwt.verify']], function () {
    //on first login the frontend sends the expo token to the backend where it is stored in the db
    Route::post('register_for_notifications', [NotificationsController::class, 'registerToken']);

    Route::get('user_profile', [AuthController::class, 'userProfile']);
    Route::post('reset_password', [AuthController::class, 'resetPass']);
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

    Route::post('fleet_request', [FleetRequestController::class, 'fleetRequest']);
    Route::post('add_destination/{id}', [FleetRequestController::class, 'addDestination']);

    Route::post('leave_request', [LeavesController::class, 'request']);
    Route::get('get_leaves_record/{status_id}', [LeavesController::class, 'getLeavesRecord']);

    Route::get('register_attendance', [AttendanceController::class, 'register']);
    Route::get('finalize_attendance', [AttendanceController::class, 'finalize']);
    Route::get('get_attendance_record', [AttendanceController::class, 'getAttendanceRecord']);


    Route::get('get_tasks/{year}/{month}', [InspectionController::class, 'getInspection']);

    Route::get('add_trip_fuel_odometer', [VehicleController::class, 'recordFuelAndOdometer']);

    Route::get('get_notifications', [NotificationsController::class, 'getNotifications']);
    //to add Notification controller routes, Vehicle controller routes, + testing them and the inspection controller

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

        // Route::get('auto_generate', [FleetRequestController::class, 'autoGenerate']);

        /* *********** routes for vehicles and fuel controller: admin APIs *********** */
        Route::post('add_department', [OrganizationController::class, 'addDepartment']);
        Route::post('add_vehicle', [VehicleController::class, 'addVehicle']);
        Route::post('view_vehicles', [VehicleController::class, 'viewVehiclesInfo']);

        /* *********** routes for Inspection controller: admin APIs *********** */
        Route::post('add_inspection_task', [InspectionController::class, 'addInspectionTask']);
        Route::get('delete_inspection_task/{id}', [InspectionController::class, 'deleteTask']);
        Route::get('get_inspection_task/{year}/{month}', [InspectionController::class, 'getInspection']);
    });
});
