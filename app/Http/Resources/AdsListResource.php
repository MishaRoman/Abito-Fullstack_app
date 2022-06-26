<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Image;
use Illuminate\Support\Facades\URL;


class AdsListResource extends JsonResource
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
            'price' => $this->price,
            'address' => $this->address,
            'category_id' => $this->category_id,
            'preview' => $this->images->first() ? URL::to($this->images->first()->title) : null,
            'favorited' => $this->favorited(),
            'created_at' => (new \DateTime($this->created_at))->format('d.m H:i'),
        ];
    }
}
