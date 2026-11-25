<?php

namespace App\Models\Concerns;

use App\Models\Media;

trait HasImageUrl
{
    public function getUrlAttribute(): ?string
    {
        return Media::urlFor($this->image);
    }
}
