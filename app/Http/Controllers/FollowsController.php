<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ad;
use App\Http\Resources\AdsListResource;

class FollowsController extends Controller
{
    /**
     * Get ads by user followings.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = auth()->user()->followings()->pluck('follower_user.user_id');
        $ads = Ad::whereIn('user_id', $users)->get();

        return AdsListResource::collection($ads);
    }

    /**
     * Follow user.
     *
     * @param App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function follow(User $user)
    {
        auth()->user()->followings()->attach($user);
        return response('success', 204);
    }

    /**
     * Unfollow user.
     *
     * @param App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function unfollow(User $user)
    {
        auth()->user()->followings()->detach($user);
        return response('success', 204);
    }
}
