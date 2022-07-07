<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'phone_number' => $this->phone_number,
            'image_url' => $this->image ? URL::to($this->image) : URL::to('images/no_avatar.png'),
            'follows' => $this->isFollowings($this->id),
            'member_since' => (new \DateTime($this->created_at))->format('d.m.Y'),
        ];
    }
}
