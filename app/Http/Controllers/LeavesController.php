<?php

namespace App\Http\Controllers;

use App\Models\Leaves;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use DateTime;
use DatePeriod;
use DateInterval;
use Illuminate\Support\Facades\Validator;
use ExpoSDK\ExpoMessage;
use ExpoSDK\Expo;
use App\Models\NotificationToken;

class LeavesController extends Controller
{
    /* *********** User APIs *********** */
    public function getLeavesRecord()
    {
        $user = Auth::user();
        $userId = $user->id;
        $leavesRecord = Leaves::where('user_id', $userId)->where('status_id', 4)->orderByDesc('date')->get();
        return json_encode(['success' => true, 'message' => 'leaves record successfully retrieved', 'attendance' => $leavesRecord]);
    }
    public function request(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $validator = Validator::make($request->all(), [
            'leave_from_date' => 'required|date|after_or_equal:today|date_format:Y-m-d',
            'leave_till_date' => 'required|date|after_or_equal:leave_from_date|date_format:Y-m-d',
            'leave_type' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $LeaveRequest = Leaves::create([
            'status_id' => 2,
            'user_id' => $userId,
            'leave_from_date' => $request->leave_from_date,
            'leave_till_date' => $request->leave_till_date,
            'leave_type' => $request->leave_type,
            'details' => $request->details,
        ]);
        $manager = $user->manager_id;
        $recipient = NotificationToken::where('user_id', '=', $manager)->pluck('ExpoToken')->all();
        $notification = Notification::create([
            'user_id' => $manager,
            'title' => 'Leave Request',
            'body' => $user->first_name . ' ' . $user->last_name . ' requested a leave.'
        ]);
        if (!empty($recipient)) {
            $expo = new Expo();
            $message = (new ExpoMessage())
                ->setTitle('Leave Request')
                ->setBody($user->first_name . ' ' . $user->last_name . ' requested a leave.')
                ->setData(['id' => 1])
                ->setChannelId('default')
                ->setBadge(0)
                ->playSound();
            $expo->send($message)->to($recipient)->push();
        }
        return json_encode([
            'success' => true,
            'message' => 'Leave request is created, it will be sent to your manager for approval',
            'LeaveRequest' => $LeaveRequest,
            'recipient' => $expo
        ]);
    }


    /* *********** Admin APIs *********** */
    public function approveByManager($id)
    {
        $LeaveRequest = Leaves::where('id', $id)->where('status_id', 2)->first();
        if (!empty($LeaveRequest)) {
            //update the status in order to send a message to the HR to approve it 
            $LeaveRequest->status_id = 3;
            $LeaveRequest->save();
            //here we send a notification to the HR to approve it too!!!
            $HR = User::where('user_type_id', 2)->pluck('id');
            $user = User::where('id', $LeaveRequest->user_id)->first();
            $recipients = NotificationToken::whereIn('user_id', $HR)->pluck('ExpoToken')->all();
            foreach ($HR as $id) {
                $notification = Notification::create([
                    'user_id' => $id,
                    'title' => 'Leave Request',
                    'body' => $user->first_name . ' ' . $user->last_name . ' has requested a leave.'
                ]);
            }
            if (!empty($recipients)) {
                $expo = new Expo();
                $message = (new ExpoMessage())
                    ->setTitle('Leave Request')
                    ->setBody($user->first_name . ' ' . $user->last_name . ' has requested a leave.')
                    ->setData(['id' => 1])
                    ->setChannelId('default')
                    ->setBadge(0)
                    ->playSound();
                $expo->send($message)->to($recipients)->push();
            }
            return json_encode(['success' => true, 'message' => 'Leave request is approved by the manager, it will be sent to HR for approval', 'Leaves' => $LeaveRequest]);
        } else {
            return json_encode(['success' => false, 'message' => 'Leave request is already approved', 'Leaves' => $LeaveRequest]);
        }
    }

    public function rejectByManager($id)
    {
        $LeaveRequest = Leaves::where('id', $id)->where('status_id', 2)->first();
        if (!empty($LeaveRequest)) {
            //update the status in order to send a message to the HR to approve it 
            $LeaveRequest->status_id = 5;
            $LeaveRequest->save();
            $user = User::where('id', $LeaveRequest->user_id)->first();
            //here we send a notification to the HR and the user!!!
            $HR = User::where('user_type_id', 2)->pluck('id');
            $recipients = NotificationToken::whereIn('user_id', $HR)->pluck('ExpoToken')->all();
            $expo = new Expo();
            foreach ($HR as $id) {
                $notification = Notification::create([
                    'user_id' => $id,
                    'title' => 'Leave Request',
                    'body' => $user->first_name . ' ' . $user->last_name . "'s leave request was rejected."
                ]);
            }
            if (!empty($recipients)) {
                $message1 = (new ExpoMessage())
                    ->setTitle('Attendance Record')
                    ->setBody($user->first_name . ' ' . $user->last_name . "'s leave request was rejected.")
                    ->setData(['id' => 1])
                    ->setChannelId('default')
                    ->setBadge(0)
                    ->playSound();
                $expo->send($message1)->to($recipients)->push();
            }
            $recipient = NotificationToken::where('user_id', $user->id)->pluck('ExpoToken')->all();
            $notification = Notification::create([
                'user_id' => $user->id,
                'title' => 'Leave Request',
                'body' => 'Your leave request was rejected.'
            ]);
            if ($recipient) {
                $message2 = (new ExpoMessage())
                    ->setTitle('Attendance Record')
                    ->setBody("Your leave request was rejected.")
                    ->setData(['id' => 1])
                    ->setChannelId('default')
                    ->setBadge(0)
                    ->playSound();
                $expo->send($message2)->to($recipient)->push();
            }
            return json_encode(['success' => true, 'message' => 'Leave request is reject by the manager', 'Leaves' => $LeaveRequest]);
        } else {
            return json_encode(['success' => false, 'message' => 'Leave request is already approved', 'Leaves' => $LeaveRequest]);
        }
    }

    public function approveByHR($id)
    {
        $LeaveRequest = Leaves::where('id', $id)->where('status_id', 3)->first();
        if (!empty($LeaveRequest)) {
            //update the status in order to send a message to the user
            $LeaveRequest->status_id = 4;
            $LeaveRequest->save();
            //here we send a notification to the user!!!
            $recipients = NotificationToken::where('user_id', $LeaveRequest->user_id)->pluck('ExpoToken')->all();
            $notification = Notification::create([
                'user_id' => $LeaveRequest->user_id,
                'title' => 'Leave Request',
                'body' => 'Your leave request was accepted.'
            ]);
            if (!empty($recipients)) {
                $expo = new Expo();
                $message = (new ExpoMessage())
                    ->setTitle('Leave Request')
                    ->setBody('Your leave request was approved.')
                    ->setData(['id' => 1])
                    ->setChannelId('default')
                    ->setBadge(0)
                    ->playSound();
                $expo->send($message)->to($recipients)->push();
            }
            return json_encode(['success' => true, 'message' => 'Leave request is approved', 'Leaves' => $LeaveRequest]);
        } else {
            return json_encode(['success' => false, 'message' => 'Leave request is already approved', 'Leaves' => $LeaveRequest]);
        }
    }

    public function getLeavesRecordPerUser(Request $request)
    {
        $markedDates = array();
        $leavesRecord = Leaves::where('user_id', '=', $request->driver_id)->where('status_id', '=', 4)->orderByDesc('leave_from_date')->get();
        $period = array();
        foreach ($leavesRecord as $Leaves) {
            $start = new DateTime($Leaves->leave_from_date);
            $end = new DateTime($Leaves->leave_till_date);
            $period = new DatePeriod(
                $start,
                new DateInterval('P1D'),
                $end->add(new DateInterval('P1D'))
            );
            foreach ($period as $p) {
                $markedDates[] = ['date' => $p->format('Y-m-d'), 'status' => $Leaves->status_id];
            }
        }
        return json_encode(['success' => true, 'message' => 'Leaves record successfully retrieved', 'Leaves' => $leavesRecord, 'marked_dates' => $markedDates]);
    }

    function getFilteredLeaves(Request $request)
    {
        $leaves = Leaves::where('status_id', '=', $request->status_id)->where('user_id', $request->user_id)->get();
        foreach ($leaves as $leave) {
            $leave->driver;
        }
        return json_encode(['success' => true, 'message' => 'Leaves record successfully retrieved', 'Leaves' => $leaves]);
    }
}
