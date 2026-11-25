<?php

namespace App\Models;

use App\Models\Concerns\HasImageUrl;
use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    use HasImageUrl;

    protected $guarded = [];
}
