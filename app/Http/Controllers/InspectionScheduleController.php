<?php

namespace App\Http\Controllers;

use App\Models\inspectionSchedule;
use Illuminate\Http\Request;

class InspectionScheduleController extends Controller
{
    public function addInspectionTask(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\inspectionSchedule  $inspectionSchedule
     * @return \Illuminate\Http\Response
     */
    public function show(inspectionSchedule $inspectionSchedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\inspectionSchedule  $inspectionSchedule
     * @return \Illuminate\Http\Response
     */
    public function edit(inspectionSchedule $inspectionSchedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\inspectionSchedule  $inspectionSchedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, inspectionSchedule $inspectionSchedule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\inspectionSchedule  $inspectionSchedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(inspectionSchedule $inspectionSchedule)
    {
        //
    }
}
