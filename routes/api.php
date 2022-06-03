<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdsController;
use App\Http\Controllers\CategoriesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/user/update', [AuthController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::controller(AdsController::class)->group(function () {
        Route::get('auth/ads', 'index');
        Route::post('/ads', 'store');
    });
    Route::get('/user/ads', [AdsController::class, 'userAds']);
    Route::get('/favorites', [AdsController::class, 'favorites']);
    Route::post('/favorite/{ad}', [AdsController::class, 'favorite']);
    Route::post('/unfavorite/{ad}', [AdsController::class, 'unfavorite']);
});

Route::get('/categories', CategoriesController::class);
Route::get('/ads', [AdsController::class, 'index']);
Route::get('/ads/{ad}', [AdsController::class, 'show']);
Route::get('/ads/author/{authorId}/{adId}', [AdsController::class, 'getAdsByAuthor']);

// Auth logins
Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);
