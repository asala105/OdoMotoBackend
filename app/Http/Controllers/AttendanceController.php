<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use ExpoSDK\ExpoMessage;
use ExpoSDK\Expo;
use App\Models\NotificationToken;
use App\Models\Notification;

class AttendanceController extends Controller
{
    /* *********** User APIs *********** */
    public function getAttendanceRecord()
    {
        $user = Auth::user();
        $userId = $user->id;
        $attendanceRecord = Attendance::where('user_id', $userId)->where('status_id', 4)->orderByDesc('date')->get();
        return json_encode(['success' => true, 'message' => 'attendance record successfully retrieved', 'attendance' => $attendanceRecord]);
    }

    public function register()
    {
        $user = Auth::user();
        $userId = $user->id;
        $registeredAttendance = Attendance::where('user_id', $userId)->where('date', date('Y-m-d'))->get()->toArray();
        if (empty($registeredAttendance)) {
            $attendance = Attendance::create([
                'status_id' => 1,
                'user_id' => $userId,
                'date' => date('Y-m-d'),
                'working_from' => date("H:i"),
                'working_to' => date("H:i"),
            ]);
            return json_encode(['success' => true, 'message' => 'attendance record is created', 'attendance' => $attendance]);
        } else {
            return json_encode(['success' => false, 'message' => 'attendance record is already created', 'attendance' => $registeredAttendance]);
        }
    }

    public function finalize()
    {
        $user = Auth::user();
        $userId = $user->id;
        $manager = $user->manager_id; //needed to send the notification
        $registeredAttendance = Attendance::where('user_id', $userId)->where('date', date('Y-m-d'))->first();
        if (empty($registeredAttendance)) {
            return json_encode(['success' => false, 'message' => 'you did not register you attendance at the begining of the day!']);
        } else if ($registeredAttendance->working_from == $registeredAttendance->working_to) {
            //update the time when he finishes his work and the status in order to send a message to the manager to approve it 
            $registeredAttendance->status_id = 2;
            $registeredAttendance->working_to = date("H:i");
            $registeredAttendance->save();
            //here we send a notification to the manager!!!
            $recipient = NotificationToken::where('user_id', '=', $manager)->pluck('ExpoToken')->all();
            $notification = Notification::create([
                'user_id' => $manager,
                'title' => 'Attendance Record',
                'body' => $user->first_name . ' ' . $user->last_name . ' registered attendance.'
            ]);
            if (!empty($recipient)) {
                $expo = new Expo();
                $message = (new ExpoMessage())
                    ->setTitle('Attendance Record')
                    ->setBody($user->first_name . ' ' . $user->last_name . ' registered attendance.')
                    ->setData(['id' => 1])
                    ->setChannelId('default')
                    ->setBadge(0)
                    ->playSound();
                $expo->send($message)->to($recipient)->push();
            }
            return json_encode(['success' => true, 'message' => 'attendance record is finalized, it will be sent to your manger for approval', 'attendance' => $registeredAttendance]);
        } else {
            return json_encode(['success' => false, 'message' => 'attendance record is already finalized', 'attendance' => $registeredAttendance]);
        }
    }

    public function approveByManager($id)
    {
        $registeredAttendance = Attendance::where('id', $id)->where('status_id', 2)->first();
        if (!empty($registeredAttendance)) {
            //update the time when he finishes his work and the status in order to send a message to the manager to approve it 
            $registeredAttendance->status_id = 3;
            $registeredAttendance->save();
            $user = User::where('id', $registeredAttendance->user_id)->first();
            //here we send a notification to the HR to approve it too!!!
            $HR = User::where('user_type_id', 2)->pluck('id');
            $recipients = NotificationToken::whereIn('user_id', $HR)->pluck('ExpoToken')->all();
            foreach ($HR as $id) {
                $notification = Notification::create([
                    'user_id' => $id,
                    'title' => 'Attendance Record',
                    'body' => $user->first_name . ' ' . $user->last_name . ' registered attendance.'
                ]);
            }
            if (!empty($recipients)) {
                $expo = new Expo();
                $message = (new ExpoMessage())
                    ->setTitle('Attendance Record')
                    ->setBody($user->first_name . ' ' . $user->last_name . ' registered attendance.')
                    ->setData(['id' => 1])
                    ->setChannelId('default')
                    ->setBadge(0)
                    ->playSound();
                $expo->send($message)->to($recipients)->push();
            }
            return json_encode(['success' => true, 'message' => 'attendance record is approved by the manager, it will be sent to HR for approval', 'attendance' => $registeredAttendance]);
            //return json_encode(['success' => true, 'message' => 'attendance record is approved by the manager, it will be sent to HR for approval', 'attendance' => $recipients]);
        } else {
            return json_encode(['success' => false, 'message' => 'attendance record is already approved', 'attendance' => $registeredAttendance]);
        }
    }

    public function rejectByManager($id)
    {
        $registeredAttendance = Attendance::where('id', $id)->where('status_id', 2)->first();
        if (!empty($registeredAttendance)) {
            //update the time when he finishes his work and the status in order to send a message to the manager to approve it 
            $registeredAttendance->status_id = 5;
            $registeredAttendance->save();
            $user = User::where('id', $registeredAttendance->user_id)->first();
            //here we send a notification to the HR and the user!!!
            $HR = User::where('user_type_id', 2)->pluck('id');
            $recipients = NotificationToken::whereIn('user_id', $HR)->pluck('ExpoToken')->all();
            foreach ($HR as $id) {
                $notification = Notification::create([
                    'user_id' => $id,
                    'title' => 'Attendance Record',
                    'body' => $user->first_name . ' ' . $user->last_name . "'s attendance was rejected."
                ]);
            }
            $expo = new Expo();
            if (!empty($recipients)) {
                $message1 = (new ExpoMessage())
                    ->setTitle('Attendance Record')
                    ->setBody($user->first_name . ' ' . $user->last_name . "'s attendance was rejected.")
                    ->setData(['id' => 1])
                    ->setChannelId('default')
                    ->setBadge(0)
                    ->playSound();
                $expo->send($message1)->to($recipients)->push();
            }
            $recipient = NotificationToken::where('user_id', $user->id)->pluck('ExpoToken')->all();
            $notification = Notification::create([
                'user_id' => $user->id,
                'title' => 'Attendance Record',
                'body' => 'Your attendance was rejected.'
            ]);
            if ($recipient) {
                $message2 = (new ExpoMessage())
                    ->setTitle('Attendance Record')
                    ->setBody("Your attendance was rejected.")
                    ->setData(['id' => 1])
                    ->setChannelId('default')
                    ->setBadge(0)
                    ->playSound();
                $expo->send($message2)->to($recipient)->push();
            }
            return json_encode(['success' => true, 'message' => 'attendance record is rejected by the manager', 'attendance' => $registeredAttendance]);
        } else {
            return json_encode(['success' => false, 'message' => 'attendance record is already approved', 'attendance' => $registeredAttendance]);
        }
    }

    public function approveByHR($id)
    {
        $registeredAttendance = Attendance::where('id', $id)->where('status_id', 3)->first();
        if (!empty($registeredAttendance)) {
            //update the status in order to "approved" 
            $registeredAttendance->status_id = 4;
            $registeredAttendance->save();
            //here we send a notification to the user!!!
            $recipients = NotificationToken::where('user_id', $registeredAttendance->user_id)->pluck('ExpoToken')->all();
            $notification = Notification::create([
                'user_id' => $registeredAttendance->user_id,
                'title' => 'Attendance Record',
                'body' => 'Your attendance was accepted.'
            ]);
            if (!empty($recipients)) {
                $expo = new Expo();
                $message = (new ExpoMessage())
                    ->setTitle('Attendance Record')
                    ->setBody('Your attendance was approved.')
                    ->setData(['id' => 1])
                    ->setChannelId('default')
                    ->setBadge(0)
                    ->playSound();
                $expo->send($message)->to($recipients)->push();
            }
            return json_encode(['success' => true, 'message' => 'attendance record is approved', 'attendance' => $registeredAttendance]);
        } else {
            return json_encode(['success' => false, 'message' => 'attendance record is already approved', 'attendance' => $registeredAttendance]);
        }
    }

    public function getAttendanceRecordPerUser(Request $request)
    {
        $markedDates = array();
        $attendanceRecord = Attendance::where('user_id', '=', $request->driver_id)->orderByDesc('date')->get();
        foreach ($attendanceRecord as $attendance) {
            $markedDates[] = ['date' => $attendance->date, 'status' => $attendance->status_id];
            $attendance->user;
        }
        return json_encode(['success' => true, 'message' => 'attendance record successfully retrieved', 'attendance' => $attendanceRecord, 'marked_dates' => $markedDates]);
    }
    public function getAttendanceRecordPerDate($date)
    {
        $data = Attendance::where('date', '=', $date)->orderByDesc('date')->get();

        foreach ($data as $d) {
            $d->user;
            $d->status;
        }
        return json_encode(['success' => true, 'message' => 'attendance record successfully retrieved', 'data' => $data]);
    }
}
