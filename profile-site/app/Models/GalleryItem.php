<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    protected $guarded = [];

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->image);
    }
}
