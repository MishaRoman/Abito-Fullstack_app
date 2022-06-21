<?php

namespace App\Http\Filters;

use App\Models\Category;

class AdsFilter extends QueryFilter
{
    public function query($value)
    {
        $this->builder->where('title', 'like', "%$value%");
    }

    public function category($value)
    {
        $category = Category::where('slug', $value)->firstOrFail();
        $this->builder->where('category_id', $category->id);
    }
}