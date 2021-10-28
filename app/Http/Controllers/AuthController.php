<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        //Validate data
        $data = $request->only(
            'department_id',
            'manager_id',
            'user_type_id',
            'first_name',
            'last_name',
            'email',
            'rank',
            'date_of_birth',
            'phone_nb',
            'password'
        );
        $validator = Validator::make($data, [
            'department_id' => 'required|integer',
            'manager_id' => 'required|integer',
            'user_type_id' => 'required|integer',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users',
            'rank' => 'required|integer',
            'date_of_birth' => 'required|date',
            'phone_nb' => 'required|string|min:4|max:17'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, create new user
        $user = User::create([
            'department_id' => $request->department_id,
            'manager_id' => $request->manager_id,
            'user_type_id' => $request->user_type_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'rank' => $request->rank,
            'date_of_birth' => $request->date_of_birth,
            'phone_nb' => $request->phone_nb,
            'password' => bcrypt($request->date_of_birth)
        ]);

        //User created, return success response
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    public function userProfile()
    {
        $user = auth()->user();
        $user->department->organization;
        return response()->json(auth()->user());
    }

    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

    function resetPass(Request $request)
    {
        if ($request->password === $request->confirm_pass) {
            $u = Auth::user();
            $user = User::where('id', $u->id)->first();
            $user->password = bcrypt($request->password);
            if ($user->first_login == 1) {
                $user->first_login = 0;
            }
            $user->save();
            return response()->json([
                'success' => true,
                'message' => 'Password successfully reset'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Password and confirm password are not matched'
            ]);
        }
    }
}
