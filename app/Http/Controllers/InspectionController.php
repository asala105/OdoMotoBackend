<?php

namespace App\Http\Controllers;

use App\Models\InspectionSchedule;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Validator;

class InspectionController extends Controller
{
    public function getInspection($year, $month)
    {
        $inspectionTasks = InspectionSchedule::whereYear('date', $year)->whereMonth('date', $month)->get()->toArray();
        return json_encode([
            'success' => true,
            'message' => 'inspection tasks retrieved successfully',
            'inspectionTasks' => $inspectionTasks
        ]);
    }
    public function addInspectionTask(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $validator = Validator::make($request->all(), [
            'date' => 'required|date|after_or_equal:today|date_format:Y-m-d',
            'inspection_type' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $inspectionSchedule = InspectionSchedule::create([
            'status_id' => 7,
            'date' => $request->date,
            'inspection_type' => $request->inspection_type,
        ]);
        return json_encode([
            'success' => true,
            'message' => 'inspection task is created',
            'insp$inspectionSchedule' => $inspectionSchedule
        ]);
    }

    public function markDone($id)
    {
        $task = InspectionSchedule::where('id', $id)->first();
        $task->update([
            'is_read' => 1
        ]);
        return json_encode([
            'success' => true,
            'message' => 'task updated successfully',
            'task' => $task
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
}
