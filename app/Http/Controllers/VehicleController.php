<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VehicleController extends Controller
{
    public function addVehicle(Request $request)
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

        //User created, return success response
        return response()->json([
            'success' => true,
            'message' => 'Vehicle added successfully',
            'data' => $vehicle
        ], 201);
    }

    public function show(Vehicle $vehicle)
    {
        //
    }

    public function edit(Vehicle $vehicle)
    {
        //
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        //
    }

    public function destroy(Vehicle $vehicle)
    {
        //
    }
}
