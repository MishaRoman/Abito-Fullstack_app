<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Ad extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function favorited()
    {
        return (bool) Favorite::where('user_id', auth('sanctum')->id())
                            ->where('ad_id', $this->id)
                            ->first();
    }

    public function scopeFilter($builder, $filters)
    {
        return $filters->apply($builder);
    }
}
