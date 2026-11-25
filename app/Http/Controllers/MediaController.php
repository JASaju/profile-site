<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function show(int $id)
    {
        $m = Media::findOrFail($id);
        return response(base64_decode($m->data), 200, [
            'Content-Type'  => $m->mime,
            'Cache-Control' => 'public, max-age=2592000, immutable',
        ]);
    }

    /** Old-style "uploads/xyz.jpg" paths: stream from disk when the file exists. */
    public function legacy(string $path)
    {
        $disk = Storage::disk('public');
        abort_unless($disk->exists($path), 404);
        return $disk->response($path);
    }
}
