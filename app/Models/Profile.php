<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $guarded = [];

    public function getPhotoUrlAttribute(): ?string
    {
        return Media::urlFor($this->photo);
    }
}
