<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Get the correct URL for an uploaded file regardless of whether it was stored locally or on Cloudinary.
     *
     * @param string|null $path
     * @param string|null $default
     * @return string
     */
    public static function url($path, $default = null)
    {
        if (empty($path)) {
            return $default ?? asset('images/placeholder.png');
        }

        // If it is a full Cloudinary (or remote) URL, return it mapped directly
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        // Return the local Storage URL mapped through asset (this adds the protocol and domain)
        return asset('storage/' . $path);
    }
}
