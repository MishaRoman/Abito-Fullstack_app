<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoriesController extends Controller
{
    public function __invoke()
    {
        return Category::all();
    }
}
