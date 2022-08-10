<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ad;
use App\Models\Comment;
use App\Http\Resources\CommentsResource;

class CommentsController extends Controller
{
    /**
     * Get all comments for ad.
     *
     * @param \App\Models\Ad $ad
     * @return \Illuminate\Http\Response
     */
    public function index(Ad $ad)
    {
        return CommentsResource::collection($ad->comments);
    }

    /**
     * Create a new comment.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models $ad
     * @return \Illuminate\Http\Response
     */
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
