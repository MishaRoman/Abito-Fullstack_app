<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;
use App\Models\Image;

class SingleAdResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'address' => $this->address,
            'author' => new UserResource(User::findOrFail($this->user_id)),
            'comments' => CommentsResource::collection($this->comments),
            'images' => AdImagesResource::collection(Image::where('ad_id', $this->id)->get()),
            'created_at' => (new \DateTime($this->created_at))->format('d.m H:i'),
        ];
    }
}
