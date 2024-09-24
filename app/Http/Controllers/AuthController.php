<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
       public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8',
        ]);

        $validatedData['password'] = bcrypt($validatedData['password']);

        $admin = Admin::create($validatedData);

        $token = $admin->createToken('authToken')->plainTextToken;

        return response()->json([
            'token' => $token,
            'admin' => $admin,
        ], 201);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $currentUser = Admin::where('email', $request->email)->first();

        if(!$currentUser || !Hash::check($request->password, $currentUser->password)){
            return response()->json(['error' => 'Incorrect credentials'], 401);
        }
        $token = $currentUser->createToken('authToken')->plainTextToken;

        return response()->json([
            'token' => $token,
            'admin' => $currentUser,
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out'], 200);
    }
}
