<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    protected $guarded = [];

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->image);
    }
}
