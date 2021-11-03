<?php

namespace App\Http\Controllers;

use App\Models\NotificationToken;
use App\Models\Notification;
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

    public function getNotifications()
    {
        $user = Auth::user();
        $userId = $user->id;
        $notifications = Notification::where('user_id', $userId)->orderByDesc('created_at')->orderByDesc('is_read')->get();
        $count = Notification::where('user_id', $userId)->where('is_read', 0)->orderByDesc('created_at')->count();
        return json_encode([
            'success' => true,
            'message' => 'notifications retrieved successfully',
            'notifications' => $notifications,
            'unread' => $count,
        ]);
    }

    public function markRead($id)
    {
        $notifications = Notification::where('id', $id)->first();
        $notifications->is_read = 1;
        $notifications->save();
        return json_encode([
            'success' => true,
            'message' => 'notifications updated successfully',
            'notifications' => $notifications
        ]);
    }
}
