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
    public function store(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $manager = $user->manager; //needed to send the notification
        $registeredAttendance = Attendance::where('user_id', $userId)->where('date', date('Y-m-d'))->get();
        if (empty($registeredAttendance)) {
            $attendance = Attendance::create([
                'status_id' => 1,
                'user_id' => $userId,
                'date' => date('Y-m-d'),
                'working_from' => date("H:i"),
                'working_to' => date("H:i"),
            ]);
            return json_encode(['success' => true, 'message' => 'attendance record is created, it will be sent to your manger for approval', 'attendance' => $attendance]);
        } else {
            return json_encode(['success' => false, 'message' => 'attendance record is already created', 'attendance' => $registeredAttendance]);
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
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendance $attendance)
    {
        //
    }
}
