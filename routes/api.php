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
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsersController;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('register_organization', [OrganizationController::class, 'store']);
Route::post('add_department', [OrganizationController::class, 'addDepartment']);

Route::group(['middleware' => ['jwt.verify']], function () {
    //on first login the frontend sends the expo token to the backend where it is stored in the db
    //used ***** only the notifications api needs modification
    /* ***********************Auth Controller: user side *********************** */
    Route::post('register_for_notifications', [NotificationsController::class, 'registerToken']);
    Route::get('user_profile', [AuthController::class, 'userProfile']);
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('reset_password', [AuthController::class, 'resetPass']);

    /***************** Notification Controller : user side ********************* */
    Route::get('get_notifications', [NotificationsController::class, 'getNotifications']);
    Route::get('mark_read/{id}', [NotificationsController::class, 'markRead']);

    /***************** Fleet Request Controller : user side ********************* */
    Route::post('fleet_request', [FleetRequestController::class, 'fleetRequest']);
    Route::post('add_destination/{id}', [FleetRequestController::class, 'addDestination']);
    Route::get('view_movement', [FleetRequestController::class, 'view_request']);
    Route::get('cancel_fleet/{id}', [FleetRequestController::class, 'cancelRequest']);

    /***************** Leaves Controller : user side ********************* */
    Route::post('leave_request', [LeavesController::class, 'request']);
    Route::get('get_leaves_record/', [LeavesController::class, 'getLeavesRecord']);

    /***************** Attendance Controller : user side ********************* */
    Route::get('register_attendance', [AttendanceController::class, 'register']);
    Route::get('finalize_attendance', [AttendanceController::class, 'finalize']);
    Route::get('get_attendance_record', [AttendanceController::class, 'getAttendanceRecord']);
    /***************** Inspection Controller : user side ********************* */
    Route::get('get_tasks/{date}', [InspectionController::class, 'getTasks']);
    Route::get('mark_task_done/{id}', [InspectionController::class, 'markDone']);

    Route::post('add_trip_fuel_odometer/{fleet_id}/{vehicle_id}', [VehicleController::class, 'recordFuelAndOdometer']);

    /***************** Admin APIs ********************* */
    Route::group(['middleware' => ['admin']], function () {
        /***************** Dashboard Controller : user side ********************* */
        Route::get('dashboard', [DashboardController::class, 'index']);

        /* *********** routes for attendance controller: admin APIs *********** */
        Route::get('approve_attendance/{id}', [AttendanceController::class, 'approveByManager']);
        Route::get('reject_attendance/{id}', [AttendanceController::class, 'rejectByManager']);
        Route::post('attendance_records', [AttendanceController::class, 'getAttendanceRecordPerUser']);
        Route::get('attendance_records_date/{date}', [AttendanceController::class, 'getAttendanceRecordPerDate']);
        Route::get('approve_attendance_hr/{id}', [AttendanceController::class, 'approveByHR']);

        /* *********** routes for leaves controller: admin APIs *********** */
        Route::get('approve_leave/{id}', [LeavesController::class, 'approveByManager']);
        Route::get('reject_leave/{id}', [LeavesController::class, 'rejectByManager']);
        Route::post('leaves_records', [LeavesController::class, 'getLeavesRecordPerUser']);
        Route::post('get_filtered_leaves', [LeavesController::class, 'getFilteredLeaves']);
        Route::get('approve_leave_hr/{id}', [LeavesController::class, 'approveByHR']);

        /* *********** routes for vehicles and fuel controller: admin APIs *********** */
        Route::get('get_departments', [OrganizationController::class, 'getAllDepartments']);
        Route::post('add_vehicle', [VehicleController::class, 'addVehicle']);
        Route::get('view_vehicles', [VehicleController::class, 'viewVehiclesInfo']);
        Route::get('delete_vehicle/{id}', [VehicleController::class, 'delete']);
        Route::get('get_users', [UsersController::class, 'getAllUsers']);
        Route::get('get_drivers', [UsersController::class, 'getAllDrivers']);
        Route::get('delete_user/{id}', [UsersController::class, 'delete']);

        /******************* FuelOdometerPerTrip and fleet ************************ */
        Route::get('fleet_requests', [FleetRequestController::class, 'getFleetRequests']);
        Route::get('fuel_odometer_values', [VehicleController::class, 'getFuelOdometerData']);
        Route::get('auto_generate', [FleetRequestController::class, 'autoGenerate']);

        /* *********** routes for Inspection controller: admin APIs *********** */
        Route::post('add_inspection_task', [InspectionController::class, 'addInspectionTask']);
        Route::get('delete_inspection_task/{id}', [InspectionController::class, 'deleteTask']);
        Route::get('get_inspection_task/{date}', [InspectionController::class, 'getInspection']);
    });
});
