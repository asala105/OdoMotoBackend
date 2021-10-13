<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Auth;

class AttendanceController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register()
    {
        $user = Auth::user();
        $userId = $user->id;
        $registeredAttendance = Attendance::where('user_id', $userId)->where('date', date('Y-m-d'))->get();
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
        $manager = $user->manager; //needed to send the notification
        $registeredAttendance = Attendance::where('user_id', $userId)->where('date', date('Y-m-d'))->first();
        if (empty($registeredAttendance)) {
            //maybe go back to store() or send an error message/ warning to the employee
            // $attendance = Attendance::create([
            //     'status_id' => 1,
            //     'user_id' => $userId,
            //     'date' => date('Y-m-d'),
            //     'working_from' => date("H:i"),
            //     'working_to' => date("H:i"),
            // ]);
            return json_encode(['success' => false, 'message' => 'you did not register you attendance at the begining of the day!']);
        } else if ($registeredAttendance->working_from == $registeredAttendance->working_to) {
            //update the time when he finishes his work and the status in order to send a message to the manager to approve it 
            $registeredAttendance->status_id = 2;
            $registeredAttendance->working_to = date("H:i");
            $registeredAttendance->save();
            //here we send a notification to the manager!!!

            return json_encode(['success' => true, 'message' => 'attendance record is finalized, it will be sent to your manger for approval', 'attendance' => $registeredAttendance]);
        } else {
            return json_encode(['success' => false, 'message' => 'attendance record is already finalized', 'attendance' => $registeredAttendance]);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function approveByManager($id)
    {
        $registeredAttendance = Attendance::where('id', $id)->where('status_id', 2)->first();
        if (!empty($registeredAttendance)) {
            //update the time when he finishes his work and the status in order to send a message to the manager to approve it 
            $registeredAttendance->status_id = 3;
            $registeredAttendance->save();
            //here we send a notification to the user and the HR to approve it too!!!

            return json_encode(['success' => true, 'message' => 'attendance record is approved by the manager, it will be sent to HR for approval', 'attendance' => $registeredAttendance]);
        } else {
            return json_encode(['success' => false, 'message' => 'attendance record is already approved', 'attendance' => $registeredAttendance]);
        }
    }


    public function approveByHR($id)
    {
        $registeredAttendance = Attendance::where('id', $id)->where('status_id', 3)->first();
        if (!empty($registeredAttendance)) {
            //update the time when he finishes his work and the status in order to send a message to the manager to approve it 
            $registeredAttendance->status_id = 4;
            $registeredAttendance->save();
            //here we send a notification to the user!!!

            return json_encode(['success' => true, 'message' => 'attendance record is approved', 'attendance' => $registeredAttendance]);
        } else {
            return json_encode(['success' => false, 'message' => 'attendance record is already approved', 'attendance' => $registeredAttendance]);
        }
    }
}
