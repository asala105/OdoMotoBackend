<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\Leaves;
use App\Models\FuelOdometerPerTrip;
use Carbon\Carbon;
use App\Models\FleetRequest;
use App\Models\InspectionSchedule;

class DashboardController extends Controller
{
    public function index()
    {
        $date = date("Y-m-d", strtotime('today'));
        //get all the drivers that do not have a leave tomorrow
        $users_on_leave = Leaves::where('leave_from_date', '<=', $date)->where('leave_till_date', '>=', $date)->pluck('user_id')->all();
        $available_drivers = User::where('user_type_id', 3)->whereNotIn('id', $users_on_leave)->get()->count();

        $vehicle_with_fuel = FuelOdometerPerTrip::where('fuel_after_trip', '>=', 70)->distinct('vehicle_id')->whereDate('updated_at', '<', Carbon::tomorrow()->subDays(1))->pluck('vehicle_id')->count();

        $number_of_fleet_requests = FleetRequest::where('date', '=', $date)->count();

        $number_of_inspection_tasks = InspectionSchedule::where('date', '=', $date)->count();

        $maintenance_freq = InspectionSchedule::select('vehicle_id', DB::raw('count(*) as counter'))
            ->where('inspection_type', 1)
            ->whereYEAR('date', date("Y-m-d", strtotime('today')))
            ->GroupBy('vehicle_id')
            ->get();

        $MaintenanceChartLabels = [];
        $MaintenanceChartData = [];
        foreach ($maintenance_freq as $freq) {
            $vehicle = $freq->vehicle;
            $MaintenanceChartLabels[] = $vehicle->registration_code;
            $MaintenanceChartData[] = $freq->counter;
        }

        $Leaves_freq = Leaves::select(DB::raw('count(*) as counter'), DB::raw('Month(leave_from_date) as month'), DB::raw('Year(leave_from_date) as year'))
            ->where('status_id', 4)
            ->whereYEAR('leave_from_date', date("Y-m-d", strtotime('today')))
            ->GroupBy('month', 'year')
            ->get();

        $LeavesChartLabels = [];
        $LeavesChartData = [];
        // foreach ($Leaves_freq as $freq) {
        //     $vehicle = $freq->vehicle;
        //     $MaintenanceChartLabels[] = $vehicle->registration_code;
        //     $MaintenanceChartData[] = $freq->counter;
        // }
        return response()->json([
            'success' => true,
            'message' => 'data retrieved successfully',
            'small_data' => [
                ['title' => 'Available Drivers', 'value' => $available_drivers],
                ['title' => 'Available Vehicles', 'value' => $vehicle_with_fuel],
                ['title' => 'Fleet Request', 'value' => $number_of_fleet_requests],
                ['title' => 'Inspection Tasks', 'value' => $number_of_inspection_tasks],
            ],
            'maintenance_chart' => [
                'ChartLabels' => $MaintenanceChartLabels,
                'ChartData' => $MaintenanceChartData,
            ],
            'leaves_chart' => [
                'ChartLabels' => $Leaves_freq,
                'ChartData' => $LeavesChartData,
            ]
        ], 201);
    }
}
