<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function getAllUsers()
    {
        $users = User::all();
        foreach ($users as $user) {
            $user->department;
            $user->manager;
        }
        return response()->json([
            'success' => true,
            'message' => 'users retrieved successfully',
            'data' => $users
        ], 201);
    }

    public function getAllDrivers()
    {
        $drivers = User::where('user_type_id', '=', 3)->get();
        foreach ($drivers as $driver) {
            $driver->department;
            $driver->manager;
        }
        return response()->json([
            'success' => true,
            'message' => 'drivers retrieved successfully',
            'data' => $drivers
        ], 201);
    }

    public function delete($id)
    {
        $user = User::where('id', $id)->delete();
        return json_encode([
            'success' => true,
            'message' => 'User deleted',
            'user' => $user
        ]);
    }
}
