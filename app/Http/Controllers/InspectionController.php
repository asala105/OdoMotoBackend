<?php

namespace App\Http\Controllers;

use App\Models\InspectionSchedule;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Validator;

class InspectionController extends Controller
{
    /* *********************Admin side************************ */
    public function getInspection($date)
    {
        $inspectionTasks = InspectionSchedule::where('date', $date)->get();
        foreach ($inspectionTasks as $task) {
            $task->driver;
            $task->vehicle;
            $task->status;
        }
        return json_encode([
            'success' => true,
            'message' => 'inspection tasks retrieved successfully',
            'inspectionTasks' => $inspectionTasks
        ]);
    }
    public function addInspectionTask(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date|after_or_equal:today|date_format:Y-m-d',
            'inspection_type' => 'required',
            'driver_id' => 'required',
            'vehicle_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $inspectionSchedule = InspectionSchedule::create([
            'status_id' => 7,
            'date' => $request->date,
            'inspection_type' => $request->inspection_type,
            'driver_id' => $request->driver_id,
            'vehicle_id' => $request->vehicle_id,
        ]);
        return json_encode([
            'success' => true,
            'message' => 'inspection task is created',
            'insp$inspectionSchedule' => $inspectionSchedule
        ]);
    }

    public function deleteTask($id)
    {
        $task = InspectionSchedule::where('id', $id)->delete();
        return json_encode([
            'success' => true,
            'message' => 'Inspection Task is canceled',
            'task' => $task
        ]);
    }

    /* *********************Driver side************************ */
    public function markDone($id)
    {
        $task = InspectionSchedule::where('id', $id)->first();
        $task->update([
            'status_id' => 6
        ]);
        return json_encode([
            'success' => true,
            'message' => 'task updated successfully',
            'task' => $task
        ]);
    }

    public function getTasks($date)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $inspectionTasks = InspectionSchedule::where('driver_id', $user_id)->where('date', $date)->get()->toArray();
        return json_encode([
            'success' => true,
            'message' => 'inspection tasks retrieved successfully',
            'inspectionTasks' => $inspectionTasks
        ]);
    }
}
