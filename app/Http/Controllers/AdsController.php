<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdRequest;
use App\Http\Resources\AdsListResource;
use App\Http\Resources\SingleAdResource;
use App\Http\Resources\UserResource;

use App\Models\Ad;
use App\Models\User;
use App\Models\Image;

use App\Http\Filters\AdsFilter;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;


class AdsController extends Controller
{
    public function index(Request $request, AdsFilter $filters)
    {
        $ads = Ad::filter($filters)->paginate(16);
        return AdsListResource::collection($ads);
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

    public function edit(Request $request)
    {
        $ad = Ad::where('id', $request->id)->firstOrFail();

        if ($ad->user_id !== auth()->id()) {
            return response('This action is unauthorize', 401);
        }

        $data = $request->validate([
            'title' => 'required|max:64',
            'description' => 'required',
            'address' => 'required|max:64',
            'price' => 'required|max:10',
        ]);

        $ad->update($data);

        $json = [
            'success' => 'true',
            'ad' => $ad,
        ];

        return response()->json($json);
    }

    public function destroy(Ad $ad) {
        if ($ad->user_id !== auth()->id()) {
            return response('This action is unauthorize', 401);
        }

        // delete ads' images
        foreach ($ad->images as $image) {
            $absolutePath = public_path($image->title);
            File::delete($absolutePath);
        }

        $ad->delete();
        
        return response('Ad was deleted successfully', 200);
    }

    public function getAdsByAuthor($authorId, $adId)
    {
        $ads = Ad::where('user_id', $authorId)->where('id', '!=', $adId)->take(8)->get();
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
