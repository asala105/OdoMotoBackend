<?php

namespace App\Http\Controllers;

use App\Models\NotificationToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    public function registerToken(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $validator = Validator::make($request->all(), [
            'ExpoToken' => 'required|string|unique:notification_tokens',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $token = NotificationToken::create(['user_id' => $userId, 'ExpoToken' => $request->ExpoToken]);
        return json_encode(['success' => true, 'message' => 'token successfully added', 'token' => $token]);
    }

    public function update(Request $request, FleetRequest $fleetRequest)
    {
        //
    }

    public function destroy(FleetRequest $fleetRequest)
    {
        //
    }
}
