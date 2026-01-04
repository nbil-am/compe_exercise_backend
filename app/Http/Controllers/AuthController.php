<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['email', 'required'],
            'password' => ['required']
        ]);
        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'error' => 'invalid credential'
            ], 403);
        }
        ;

        $user->tokens()->delete();
        $token = $user->createToken('accessToken')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'user' => $user
        ]);

    }

    public function register(Request $request)
    {
        $validate= Validator::make($request->all(), [
            'name'=>'required|string',
            'email'=>'required|email|unique:users',
            'password'=>'required|confirmed|min:6'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(),403);
        }
        $validated = $validate->validated();
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);

        $token = $user->createToken('access_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'user' => $user
        ]);
    }
    public function logOut(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        return response()->json(['message' => 'User has been logged out successfully']);
    }
}
