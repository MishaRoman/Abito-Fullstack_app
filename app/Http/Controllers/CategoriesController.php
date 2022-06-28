<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class CategoriesController extends Controller
{
    public function __invoke()
    {
        $categories = Cache::rememberForever('categories', function() {
            Category::get();
        });
        return $categories;
    }
}
