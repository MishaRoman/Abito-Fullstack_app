<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\ImagesService;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|string|unique:users,email',
            'phone_number' => 'required|digits_between:10,13|unique:users,phone_number',
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
            'user' => new UserResource($user),
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
        $user = new UserResource(Auth::user());
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
        $user = Auth::user();
        return new UserResource($user);
    } 

    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        $data = $request->validated();

        if(isset($data['image'])) {
            $relativePath = ImagesService::saveImage($data['image']);
            $data['image'] = $relativePath;

            // If there is an old image, delete it
            if ($user->image) {
                ImagesService::deleteImage($user->image);
            }
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
