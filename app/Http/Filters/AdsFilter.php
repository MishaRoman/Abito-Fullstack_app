<?php

namespace App\Http\Filters;

use App\Models\Category;

class AdsFilter extends QueryFilter
{
    public function query(string $value)
    {
        $this->builder->where('title', 'like', "%$value%");
    }

    public function category(string $value)
    {
        $category = Category::where('slug', $value)->firstOrFail();
        $this->builder->where('category_id', $category->id);
    }
}