<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdRequest;
use App\Http\Resources\AdsListResource;
use App\Http\Resources\SingleAdResource;
use App\Models\Ad;
use Carbon\Carbon;

class AdsController extends Controller
{
    public function index()
    {
        return AdsListResource::collection(Ad::get());
    }

    public function store(StoreAdRequest $request)
    {
        $data = $request->validated();

        $user = $request->user();

        $ad = Ad::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'price' => $data['price'],
            'category_id' => $data['category'],
            'address' => $data['address'],
            'expire_date' => Carbon::now()->addDays(30),
            'user_id' => auth()->id()
        ]);

        return response([
            'ad' => $ad
        ]);
    }

    public function show(Ad $ad)
    {
        return new SingleAdResource($ad);
    }
}
