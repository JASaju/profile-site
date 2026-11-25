<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documentary extends Model
{
    protected $guarded = [];

    /** Turn any YouTube watch / short / youtu.be link into an embed URL. */
    public function getEmbedUrlAttribute(): ?string
    {
        $pattern = '~(?:youtu\.be/|youtube\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/|live/))([A-Za-z0-9_-]{11})~';
        if (preg_match($pattern, $this->youtube_url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }
        return null;
    }
}
