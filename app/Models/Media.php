<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

/** An uploaded image kept in the database (works on hosts with no persistent disk, e.g. Wasmer). */
class Media extends Model
{
    protected $table = 'media_files';
    protected $guarded = [];

    /** Save an upload and return the reference string kept in the image/photo column, e.g. "media/7". */
    public static function storeUpload(UploadedFile $file): string
    {
        $m = static::create([
            'mime' => $file->getMimeType() ?: 'image/jpeg',
            'data' => base64_encode(file_get_contents($file->getRealPath())),
        ]);
        return 'media/' . $m->id;
    }

    /** Remove the stored image behind a reference string (no-op for legacy file paths). */
    public static function forget(?string $ref): void
    {
        if ($ref && preg_match('#^media/(\d+)$#', $ref, $m)) {
            static::whereKey($m[1])->delete();
        }
    }

    /** Public URL for a reference string; legacy "uploads/..." paths are served from disk if the file exists. */
    public static function urlFor(?string $ref): ?string
    {
        if (!$ref) {
            return null;
        }
        if (preg_match('#^media/(\d+)$#', $ref, $m)) {
            return route('media', $m[1], false);
        }
        return route('legacy-file', ['path' => $ref], false);
    }
}
