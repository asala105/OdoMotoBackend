<?php

namespace App\Http\Controllers;

use App\Models\FuelOdometerPerTrip;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VehicleController extends Controller
{
    public function addVehicle(Request $request)
    {
        $user = Auth::user();
        $orgId = $user->organization_id;
        //Validate data
        $data = $request->only(
            'driver_id',
            'category',
            'registration_code',
            'plate_number',
            'model',
            'weight',
            'odometer',
            'fuel_level',
            'is_rented',
            'driver_license_requirements'
        );
        $validator = Validator::make($data, [
            'driver_id' => 'required|integer',
            'category' => 'required|string',
            'registration_code' => 'required|string',
            'plate_number' => 'required|string',
            'model' => 'required|string',
            'weight' => 'required',
            'odometer' => 'required',
            'fuel_level' => 'required',
            'is_rented' => 'required|boolean'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, create new user
        $vehicle = Vehicle::create([
            'driver_id' => $request->driver_id,
            'category' => $request->category,
            'registration_code' => $request->registration_code,
            'plate_number' => $request->plate_number,
            'model' => $request->model,
            'weight' => $request->weight,
            'odometer' => $request->odometer,
            'fuel_level' => $request->fuel_level,
            'is_rented' => $request->is_rented,
            'driver_license_requirements' => $request->driver_license_requirements,
        ]);
        $vehicle->organization_id = $orgId;
        $vehicle->save();
        return response()->json([
            'success' => true,
            'message' => 'Vehicle added successfully',
            'data' => $vehicle
        ], 201);
    }

    public function viewVehiclesInfo()
    {
        $user = Auth::user();
        $orgId = $user->organization_id;
        $vehicles = Vehicle::where('organization_id', '=', $orgId)->get();
        foreach ($vehicles as $vehicle) {
            $vehicle->driver;
        }
        return response()->json([
            'success' => true,
            'message' => 'Vehicle retrieved successfully',
            'data' => $vehicles
        ], 201);
    }

    public function edit($id, Request $request)
    {
        //Validate data
        $data = $request->only(
            'driver_id',
            'category',
            'registration_code',
            'plate_number',
            'model',
            'weight',
            'odometer',
            'fuel_level',
            'is_rented',
            'driver_license_requirements'
        );
        $validator = Validator::make($data, [
            'driver_id' => 'required|integer',
            'category' => 'required|string',
            'registration_code' => 'required|string',
            'plate_number' => 'required|string',
            'model' => 'required|string',
            'weight' => 'required',
            'odometer' => 'required',
            'fuel_level' => 'required',
            'is_rented' => 'required|boolean',
            'driver_license_requirements' => 'required|string'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, create new user
        $vehicle = Vehicle::where('id', $id)->update([
            'driver_id' => $request->driver_id,
            'category' => $request->category,
            'registration_code' => $request->registration_code,
            'plate_number' => $request->plate_number,
            'model' => $request->model,
            'weight' => $request->weight,
            'odometer' => $request->odometer,
            'fuel_level' => $request->fuel_level,
            'is_rented' => $request->is_rented,
            'driver_license_requirements' => $request->driver_license_requirements,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Vehicle added successfully',
            'data' => $vehicle
        ], 201);
    }

    public function recordFuelAndOdometer(Request $request, $fleet_id, $vehicle_id)
    {
        $user = Auth::user();
        $orgId = $user->organization_id;
        $validator = Validator::make($request->all(), [
            'odometer_before_trip' => 'required',
            'odometer_after_trip' => 'required',
            'fuel_before_trip' => 'required',
            'fuel_after_trip' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $FuelOdo = FuelOdometerPerTrip::create([
            'vehicle_id' => $vehicle_id,
            'fleet_request_id' => $fleet_id,
            'odometer_before_trip' => $request->odometer_before_trip,
            'odometer_after_trip' => $request->odometer_after_trip,
            'fuel_before_trip' => $request->fuel_before_trip,
            'fuel_after_trip' => $request->fuel_after_trip,
        ]);
        $FuelOdo->organization_id = $orgId;
        $FuelOdo->save();
        return json_encode([
            'success' => true,
            'message' => 'Data successfully added',
            'FuelOdo' => $FuelOdo,
        ]);
    }

    public function delete($id)
    {
        $vehicle = Vehicle::where('id', $id)->delete();
        return json_encode([
            'success' => true,
            'message' => 'Vehicle deleted',
            'vehicle' => $vehicle
        ]);
    }

    public function getFuelOdometerData()
    {
        $user = Auth::user();
        $orgId = $user->organization_id;
        $FuelOdo = FuelOdometerPerTrip::where('organization_id', $orgId)->get();
        foreach ($FuelOdo as $Fuel) {
            $Fuel->fleet;
            $Fuel->vehicle;
        }
        return json_encode([
            'success' => true,
            'message' => 'Data retrieved successfully',
            'data' => $FuelOdo
        ]);
    }
}
