<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function register(Request $request)
    {
        try {

            $request->validate([
                'name' => ['required', 'string', 'max: 255'],
                'email' => ['required', 'email', 'string', 'max: 255', 'unique:users'],
                'username' => ['required', 'string', 'max: 255'],
                'phone' => ['required', 'string', 'max: 13'],
                'password' => ['required', 'string', 'min: 5', 'max:255'],
            ]);

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);

            $users = User::where('email', $request->email)->first();
            $tokenResult = createToken('authToken')->plainTextToken();

            return ResponseFormatter::success([
                'Access Token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $users,
            ], 'User Registered');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error,
            ], 'Authentication Failed', 500);
        }
    }

    // public function login(Request $request)
    // {
    //     try {
    //         $request->validate([
    //             'email' => ['required', 'max: 255'],
    //             'password' = > ['required', 'max: 255'],
    //         ]);
    //     } catch (\Throwable $th) {
    //         //throw $th;
    //     }

    // }
}
