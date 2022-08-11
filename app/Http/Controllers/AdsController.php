<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdRequest;
use App\Http\Requests\UpdateAdRequest;
use App\Http\Resources\AdsListResource;
use App\Http\Resources\SingleAdResource;
use App\Http\Resources\UserResource;

use App\Models\Ad;
use App\Models\User;
use App\Models\Image;

use App\Services\ImagesService;

use App\Http\Filters\AdsFilter;
use Carbon\Carbon;


class AdsController extends Controller
{
    /**
     * Get all ads.
     *
     * @param \App\Http\Filters\AdsFilter $filters
     * @return \App\Http\Resources\AdsListResource
     */
    public function index(AdsFilter $filters)
    {
        $ads = Ad::filter($filters)->paginate(16);
        return AdsListResource::collection($ads);
    }

    /**
     * Create a new ad.
     *
     * @param \App\Http\Requests\StoreAdRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAdRequest $request)
    {
        $data = $request->validated();

        $user = $request->user();

        $images = $data['images'];
        unset($data['images']);

        $ad = Ad::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'price' => $data['price'],
            'category_id' => $data['category'],
            'address' => $data['address'],
            'expire_date' => Carbon::now()->addDays(30),
            'user_id' => auth()->id()
        ]);

        foreach($images as $image) {
            $relativePath = ImagesService::saveImage($image);
            Image::create([
                'title' => $relativePath,
                'ad_id' => $ad->id
            ]);
        }

        return response([
            'ad' => $ad
        ]);
    }

    /**
     * Get the ad by id.
     *
     * @param \App\Models\Ad $ad
     * @return \App\Http\Resources\SingleAdResource
     */
    public function show(Ad $ad)
    {
        return new SingleAdResource($ad);
    }

    /**
     * Edit an ad.
     *
     * @param \App\Http\Requests\UpdateAdRequest $request
     * @return \Illuminate\Http\Response
     */
    public function edit(UpdateAdRequest $request)
    {
        $ad = Ad::where('id', $request->id)->firstOrFail();

        if ($ad->user_id !== auth()->id()) {
            return response('This action is unauthorize', 401);
        }

        $data = $request->validated();

        $ad->update($data);

        $json = [
            'success' => 'true',
            'ad' => $ad,
        ];

        return response()->json($json);
    }

    /**
     * Delete an ad.
     *
     * @param \App\Models\Ad $ad
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ad $ad)
    {
        if ($ad->user_id !== auth()->id()) {
            return response('This action is unauthorize', 401);
        }

        // delete ads' images
        foreach ($ad->images as $image) {
            // Delete image file
            ImagesService::deleteImage($image->title);
            // Delete image from database
            $image->delete();
        }

        $ad->delete();
        
        return response('Ad was deleted successfully', 204);
    }

    /**
     * Get ads by user id.
     *
     * @param int $userId
     * @param int $adId
     * @return \App\Http\Resources\AdsListResource
     */
    public function getAdsByAuthor(int $userId, int $adId)
    {
        $ads = Ad::where('user_id', $userId)->where('id', '!=', $adId)->take(8)->get();
        return AdsListResource::collection($ads);
    }

    /**
     * Get favorite ads for auth user
     *
     * @return \App\Http\Resources\AdsListResource
     */
    public function favorites()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $ads = $user->favorites;
        return AdsListResource::collection($ads);
    }

    /**
     * Add an ad to favorites.
     *
     * @param \App\Models\Ad $ad
     * @return \Illuminate\Http\Response
     */
    public function favorite(Ad $ad)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $user->favorites()->attach($ad->id);
        return response('success', 204);
    }

    /**
     * Remove ad from favorites.
     *
     * @param \App\Models\Ad $ad
     * @return \Illuminate\Http\Response
     */
    public function unfavorite(Ad $ad)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $user->favorites()->detach($ad->id);
        return response('success', 204);
    }

    /**
     * Get all ads for auth user.
     *
     * @return \App\Http\Resources\AdsListResource
     */
    public function myAds()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $ads = $user->ads;
        return AdsListResource::collection($ads);;
    }

    /**
     * Get all ads by user id.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function userAds(User $user)
    {
        $ads = AdsListResource::collection($user->ads);
        $user = new UserResource($user);

        $json = [
            'data' => [
                'ads' => $ads,
                'user' => $user
            ]
        ];
        return response()->json($json);
    }
}
