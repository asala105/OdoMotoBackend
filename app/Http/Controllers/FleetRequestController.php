<?php

namespace App\Http\Controllers;

use App\Models\FleetRequest;
use App\Models\Destination;
use App\Models\Notification;
use App\Models\NotificationToken;
use App\Models\Leaves;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use ExpoSDK\ExpoMessage;
use ExpoSDK\Expo;
use App\Models\Vehicle;

class FleetRequestController extends Controller
{
    /* ************** Users APIs:*************** */
    public function fleetRequest(Request $request)
    {
        $user = Auth::user();
        $depId = $user->department->id;
        $userId = $user->id;
        $orgId = $user->organization_id;
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
            'user_id' => $userId,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'purpose' => $request->purpose,
        ]);
        $FleetRequest->organization_id = $orgId;
        $FleetRequest->save();
        return json_encode([
            'success' => true,
            'message' => 'Fleet request is created, you will be notified with the drivers name before the date of the request',
            'Fleet' => $FleetRequest
        ]);
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

    public function view_request()
    {
        $user = Auth::user();
        $userId = $user->id;
        $movement = FleetRequest::where('date', '=', date("Y-m-d", strtotime('today')))->where('driver_id', $userId)->get()->first();
        $movement2 = FleetRequest::where('date', '=', date("Y-m-d", strtotime('today')))->where('user_id', $userId)->get()->first();

        if ($movement2) {
            $movement2->destinations;
            $movement2->driver;
            $movement2->vehicle;
            $movement2->department;
            return json_encode([
                'success' => true,
                'message' => 'Movement plan for the employee retrieved successfully',
                'movement' => $movement2,
            ]);
        } else if ($movement) {
            $movement->destinations;
            $movement->driver;
            $movement->vehicle;
            $movement->department;
            return json_encode([
                'success' => true,
                'message' => 'Movement plan for the driver retrieved successfully',
                'movement' => $movement,
            ]);
        } else {
            return json_encode([
                'success' => true,
                'message' => 'No movement plans for the current user',
                'movement' => null,
            ]);
        }
    }

    /* **********************Admin APIs*************************** */
    public function autoGenerate()
    {
        $user = Auth::user();
        $orgId = $user->organization_id;
        $date = date("Y-m-d", strtotime('tomorrow'));
        //get all the drivers that do not have a leave tomorrow
        $users_on_leave = Leaves::where('organization_id', '=', $orgId)->where('leave_from_date', '<=', $date)->where('leave_till_date', '>=', $date)->pluck('user_id')->all();
        $available_vehicles = Vehicle::where('organization_id', '=', $orgId)->whereNotIn('driver_id', $users_on_leave)->pluck('id')->all();
        $fleet = FleetRequest::where('organization_id', '=', $orgId)->where('date', '=', date("Y-m-d", strtotime('tomorrow')))->get();
        foreach ($fleet as $fl) {
            shuffle($available_vehicles);
            $random_vehicle = array_pop($available_vehicles);
            $fl->vehicle_id = $random_vehicle;
            $vehicle = Vehicle::where('id', $random_vehicle)->first();
            $fl->driver_id = $vehicle->driver_id;
            $fl->save();
            $fl->destinations;
            $fl->vehicle;
            $fl->driver;
            $fl->department;

            $notification1 = Notification::create([
                'user_id' => $fl->user_id,
                'title' => 'Movement Plan',
                'body' => 'The movement plan for tomorrow is generated.',
            ]);
            $notification1->type = 'Info';
            $notification1->save();

            $notification2 = Notification::create([
                'user_id' => $fl->driver_id,
                'title' => 'Movement Plan',
                'body' => 'The movement plan for tomorrow is generated.',
            ]);
            $notification2->type = 'Info';
            $notification2->save();
            $recipients = NotificationToken::where('user_id', $fl->user_id)->orWhere('driver_id', $fl->driver_id)->pluck('ExpoToken')->all();
            if (!empty($recipients)) {
                $expo = new Expo();
                $message = (new ExpoMessage())
                    ->setTitle('Movement Plan')
                    ->setBody('The movement plan for tomorrow is generated.')
                    ->setData(['id' => 1])
                    ->setChannelId('default')
                    ->setBadge(0)
                    ->playSound();
                $expo->send($message)->to($recipients)->push();
            }
        }
        return json_encode([
            'success' => true,
            'message' => 'Fleet request is generated',
            'generated' => $fleet
        ]);
    }

    public function getFleetRequests()
    {
        $user = Auth::user();
        $orgId = $user->organization_id;
        $fleet = FleetRequest::where('organization_id', '=', $orgId)->where('date', '=', date("Y-m-d", strtotime('tomorrow')))->get();
        foreach ($fleet as $f) {
            $f->destinations;
            $f->vehicle;
            $f->driver;
            $f->department;
        }
        return json_encode([
            'success' => true,
            'message' => 'Fleet retrieved',
            'Fleet' => $fleet,
        ]);
    }
}
