<?php

namespace App\Http\Controllers;

use App\Models\Leaves;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Validator;

class LeavesController extends Controller
{
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
        $organization = Leaves::create([
            'status_id' => 2,
            'user_id' => $userId,
            'leave_from_date' => $request->leave_from_date,
            'leave_till_date' => $request->leave_till_date,
            'leave_type' => $request->leave_type,
            'details' => $request->details,
        ]);
        return json_encode([
            'success' => true,
            'message' => 'Leave request is created, it will be sent to your manager for approval',
            'organization' => $organization
        ]);
        //we still need to add notification and email notification to the manager
    }

    public function approveByManager($id)
    {
        $LeaveRequest = Leaves::where('id', $id)->where('status_id', 2)->first();
        if (!empty($LeaveRequest)) {
            //update the status in order to send a message to the HR to approve it 
            $LeaveRequest->status_id = 3;
            $LeaveRequest->save();
            //here we send a notification to the HR to approve it!!!

            return json_encode(['success' => true, 'message' => 'Leave request is approved by the manager, it will be sent to HR for approval', 'Leaves' => $LeaveRequest]);
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

            return json_encode(['success' => true, 'message' => 'Leave request is approved', 'Leaves' => $LeaveRequest]);
        } else {
            return json_encode(['success' => false, 'message' => 'Leave request is already approved', 'Leaves' => $LeaveRequest]);
        }
    }

    public function getLeavesRecordPerUser()
    {
        $LeavesRecords = Leaves::where('status_id', 4)->orderByDesc('leave_from_date', 'leave_till_date', 'leave_type')->get()->groupBy('user_id');
        return json_encode(['success' => true, 'message' => 'Leaves records successfully retrieved', 'Leaves' => $LeavesRecords]);
    }
}
