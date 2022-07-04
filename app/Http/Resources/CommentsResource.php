<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Image;
use Illuminate\Support\Facades\URL;
use App\Models\User;


class CommentsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'author' => new UserResource($this->user),
            'ad_id' => $this->ad_id,
            'created_at' => (new \DateTime($this->created_at))->format('d.m.y H:i'),
        ];
    }
}
