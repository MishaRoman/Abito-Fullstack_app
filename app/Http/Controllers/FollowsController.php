<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class FollowsController extends Controller
{
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
