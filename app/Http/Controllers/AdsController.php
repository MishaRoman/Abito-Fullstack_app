<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdRequest;
use App\Http\Resources\AdsListResource;
use App\Http\Resources\SingleAdResource;
use App\Http\Resources\UserResource;
use App\Models\Ad;
use App\Models\User;
use App\Models\Image;
use Carbon\Carbon;
use Illuminate\Support\Str;


class AdsController extends Controller
{
    public function index()
    {
        return AdsListResource::collection(Ad::paginate(16));
    }

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
            $relativePath = $this->saveImage($image);
            Image::create([
                'title' => $relativePath,
                'ad_id' => $ad->id
            ]);
        }

        return response([
            'ad' => $ad
        ]);
    }

    public function show(Ad $ad)
    {
        return new SingleAdResource($ad);
    }

    public function getAdsByAuthor($authorId, $adId)
    {
        $ads = Ad::where('user_id', $authorId)->where('id', '!=', $adId)->get();
        return AdsListResource::collection($ads);
    }

    public function favorites()
    {
        $user = auth()->user();
        $ads = $user->favorites;
        return AdsListResource::collection($ads);
    }

    public function favorite(Ad $ad)
    {
        $user = auth()->user();
        $user->favorites()->attach($ad->id);
        return 'success';
    }

    public function unfavorite(Ad $ad)
    {
        $user = auth()->user();
        $user->favorites()->detach($ad->id);
        return 'success';
    }

    public function myAds()
    {
        $user = auth()->user();
        $ads = $user->ads;
        return AdsListResource::collection($ads);;
    }

    public function userAds($id)
    {
        $user = User::where('id', $id)->firstOrFail();

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

    protected function saveImage($image)
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $image, $type)) {
            // Take out the base64 encoded text without mime type
            $image = substr($image, strpos($image, ',') + 1);
            // Get file extension
            $type = strtolower($type[1]);

            // Check if file is an image
            if (!in_array($type, ['jpg', 'jpeg', 'png'])) {
                throw new \Exception('invalid image type');
            }

            $image = str_replace(' ', '+', $image);
            $image = base64_decode($image);

            if ($image === false) {
                throw new \Exception('base64_decode failed');
            }
        } else {
            throw new \Exception('did not match data URI with image data');
        }
        $dir = 'images/';
        $name = Str::random() . '.' . $type;

        $relativePath = $dir . $name;
        file_put_contents($relativePath, $image);
        return $relativePath;
    }
}
