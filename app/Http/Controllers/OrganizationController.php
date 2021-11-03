<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrganizationController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:organizations',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $organization = Organization::create(['name' => $request->name]);
        return json_encode(['success' => true, 'message' => 'organization successfully added', 'organization' => $organization]);
    }

    // Add a new department
    public function addDepartment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->organization_id) {
            $org = $request->organization_id;
        } else {
            $user = Auth::user();
            $org = $user->department->organization;
        }
        $department = Department::create(['name' => $request->name, 'organization_id' => $org->id]);
        return json_encode(['success' => true, 'message' => 'department successfully added', 'department' => $department]);
    }

    public function getAllDepartments()
    {
        $deps = Department::all();
        foreach ($deps as $dep) {
            $dep->driver;
        }
        return response()->json([
            'success' => true,
            'message' => 'Departments retrieved successfully',
            'data' => $deps
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Organization $organization)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function destroy(Organization $organization)
    {
        //
    }
}
