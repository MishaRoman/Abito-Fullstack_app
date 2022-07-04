<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ad;
use App\Models\Comment;
use App\Http\Resources\CommentsResource;

class CommentsController extends Controller
{
    public function index(Ad $ad)
    {
        return CommentsResource::collection($ad->comments);
    }

    public function store(Request $request, Ad $ad)
    {
        $data = $request->validate([
            'body' => 'required|max:256',
        ]);

        $comment = $ad->comments()->create([
            'body' => $data['body'],
            'user_id' => auth()->id(),
        ]);
        return response($comment, 201);
    }
}
