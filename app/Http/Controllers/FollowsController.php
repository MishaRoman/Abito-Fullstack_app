<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ad;
use App\Http\Resources\AdsListResource;

class FollowsController extends Controller
{
    public function index()
    {
        $users = auth()->user()->followings()->pluck('follower_user.user_id');
        $ads = Ad::whereIn('user_id', $users)->get();

        return AdsListResource::collection($ads);
    }

    public function follow(User $user)
    {
        auth()->user()->followings()->attach($user);
        return response('success', 200);
    }

    public function unfollow(User $user)
    {
        auth()->user()->followings()->detach($user);
        return response('success', 200);
    }
}
