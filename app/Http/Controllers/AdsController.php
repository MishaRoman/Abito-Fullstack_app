<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdRequest;
use App\Models\Ad;
use Carbon\Carbon;

class AdsController extends Controller
{
    public function store(StoreAdRequest $request)
    {
        $data = $request->validated();

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
}
