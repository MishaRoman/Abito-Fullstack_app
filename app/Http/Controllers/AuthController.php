<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|string|unique:users,email',
            'phone_number' => 'required|min:10|max:13|unique:users,phone_number',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'password' => bcrypt($data['password']),
        ]);

        $token = $user->createToken('main')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);

        if(!Auth::attempt($credentials)) {
            $json = [
                'success' => false,
                'error' => [
                    'message' => [
                        'email' => ['The provided credentials are not correct']
                    ],
                ],
            ];
            return response()->json($json, 422);
        }
        $user = Auth::user();
        $token = $user->createToken('main')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout()
    {
        $user = Auth::user();

        $user->currentAccessToken()->delete();

        return response([
            'success' => true
        ]);
    }

    public function user()
    {
        return Auth::user();
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        if($user->phone_number == $request->phone_number) {
            $data = $request->validate([
                'name' => 'required|string',
            ]);
        } else {
            $data = $request->validate([
                'name' => 'required|string',
                'phone_number' => 'required|min:10|max:13|unique:users,phone_number',
            ]);
        }

        $user->update($data);

        $json = [
            'success' => 'true',
            'message' => 'Profile has been updated successfully',
            'user' => $user,
        ];

        return response()->json($json);
    }

    
}
