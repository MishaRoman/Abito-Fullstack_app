<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\ImagesService;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    /**
     * Register new user.
     * 
     * @param \App\Http\Requests\RegisterUserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterUserRequest $request)
    {
        $data = $request->validated();

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

    /**
     * Login user.
     * 
     * @param \App\Http\Requests\LoginRequest $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

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

    /**
     * Logout user.
     * 
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        $user = Auth::user();

        $user->currentAccessToken()->delete();

        return response([
            'success' => true
        ]);
    }

    /**
     * Get auth user.
     * 
     * @return \App\Http\Resources\UserResource
     */
    public function user()
    {
        $user = Auth::user();
        return new UserResource($user);
    } 

    /**
     * Update user profile.
     * 
     * @param \App\Http\Requests\UpdateProfileRequest $request
     * @return \Illuminate\Http\Response
     */
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
