<?php

namespace App\Http\Controllers;

use App\Models\Leaves;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Validator;

class LeavesController extends Controller
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
        $validator = Validator::make($request->all(), [
            'leave_from_date' => 'required|date|after_or_equal:today|date_format:Y-m-d',
            'leave_till_date' => 'required|date|after_or_equal:leave_from_date|date_format:Y-m-d',
            'leave_type' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $organization = Leaves::create([
            'status_id' => 1,
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Leaves  $leaves
     * @return \Illuminate\Http\Response
     */
    public function show(Leaves $leaves)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Leaves  $leaves
     * @return \Illuminate\Http\Response
     */
    public function edit(Leaves $leaves)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Leaves  $leaves
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Leaves $leaves)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Leaves  $leaves
     * @return \Illuminate\Http\Response
     */
    public function destroy(Leaves $leaves)
    {
        //
    }
}
