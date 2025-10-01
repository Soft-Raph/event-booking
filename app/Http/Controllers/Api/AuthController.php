<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name'=>'required|string|max:120',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|string|min:8',
            'phone'=>'nullable|string|max:40',
            'role'=>'required|in:admin,organizer,customer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success'=>false,
                'message'=>'Validation error',
                'errors'=>$validator->errors(),
            ], 422);
        }
        $data = $validator->validated();

        $user = User::create([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'password'=>Hash::make($data['password']),
            'phone'=>$data['phone'] ?? null,
            'role'=>$data['role'],
        ]);

        return response()->json(['success'=>true,'message'=>'Registered','data'=>$user], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'=>'required|email',
            'password'=>'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success'=>false,
                'message'=>'Validation error',
                'errors'=>$validator->errors(),
            ], 422);
        }
        $data = $validator->validated();

        $user = User::where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'success'=>false,
                'message'=>'The provided credentials are incorrect.',
            ], 422);
        }
        $token = $user->createToken('api')->plainTextToken;
        return $this->ok(['token'=>$token,'user'=>$user], 'Logged in');
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->ok(null, 'Logged out');
    }

    public function me(Request $request)
    {
        return $this->ok($request->user(), 'Current user');
    }
}
