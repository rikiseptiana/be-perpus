<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller 
{
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $roleUser = Role::where('name', 'user')->first();
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $roleUser->id
        ]);

        $token = JWTAuth::fromUser($user);
        return response()->json([
            'message' => 'register berhasil',
            'token' => $token,
            'user' => $user
        ], 201);
        
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $credentials = $request->only('email', 'password');
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $token = JWTAuth::fromUser(auth()->user()->load('role'));
        return response()->json([
            'message' => 'login berhasil',
            'token' => $token,
            'user' => auth()->user()
        ], 201);
    }
    public function getUser() {
        $user = auth()->user();
        $currentUser = User::with('role')->find($user->id);

        return response()->json([
            "message"=>"berhasil get user",
            "user"=> $currentUser
        ]);
    }

    public function logout() {
        auth()->logout();
        return response()->json(
            ['message' => 'Berhasil Logout']);
    }
};
