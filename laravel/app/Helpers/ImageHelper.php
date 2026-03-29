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
        // Simple inline SVG placeholder to fully avoid 404s on missing files
        $placeholder = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Crect width='100' height='100' fill='%23f3f0ff'/%3E%3Ctext x='50' y='54' text-anchor='middle' font-size='28' fill='%237c3aed'%3E%F0%9F%93%A6%3C/text%3E%3C/svg%3E";

        if (empty($path)) {
            return $default ?? $placeholder;
        }

        // If it is a full Cloudinary (or remote) URL, return it directly
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        // If it's a local file but wiped by Render, return the placeholder to PREVENT browser 404 console errors
        if (!Storage::disk('public')->exists($path)) {
            return $default ?? $placeholder;
        }

        // Return the valid local Storage URL mapped through asset
        return asset('storage/' . $path);
    }
}
