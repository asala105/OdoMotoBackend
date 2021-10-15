<?php

namespace App\Http\Controllers;

use App\Models\FleetRequest;
use App\Models\Destination;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Validator;
use ExpoSDK\ExpoMessage;
use ExpoSDK\Expo;
use App\Models\NotificationToken;

class FleetRequestController extends Controller
{
    /* ************** Users APIs:*************** */
    public function fleetRequest(Request $request)
    {
        $user = Auth::user();
        $depId = $user->department->id;
        $validator = Validator::make($request->all(), [
            'date' => 'required|date|after:today|date_format:Y-m-d',
            'start_time' => 'required',
            'end_time' => 'required',
            'purpose' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $FleetRequest = FleetRequest::create([
            'department_id' => $depId,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'purpose' => $request->purpose,
        ]);
        return json_encode([
            'success' => true,
            'message' => 'Fleet request is created, you will be notified with the drivers name before the date of the request',
            'Fleet' => $FleetRequest
        ]);
    }

    public function show(FleetRequest $fleetRequest)
    {
        //
    }

    public function edit(FleetRequest $fleetRequest)
    {
        //
    }

    public function addDestination(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'location_from' => 'required|string',
            'location_to' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $destination = Destination::create([
            'fleet_request_id' => $id,
            'location_from' => $request->location_from,
            'location_to' => $request->location_to,
        ]);
        return json_encode([
            'success' => true,
            'message' => 'Destination is added to the fleet request, you will be notified with the drivers name before the date of the request',
            'destination' => $destination
        ]);
    }

    public function cancelRequest($id)
    {
        $fleet = FleetRequest::where('id', $id)->delete();
        return json_encode([
            'success' => true,
            'message' => 'Fleet request is canceled',
            'fleet' => $fleet
        ]);
    }
}
