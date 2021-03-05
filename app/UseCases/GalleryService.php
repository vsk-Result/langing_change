<?php

namespace App\UseCases;

use File;
use Illuminate\Support\Str;

class GalleryService
{
    public function getImages(): array
    {
        return File::files(public_path('images'));
    }

    public function uploadImages(array $images): void
    {
        foreach ($images as $image) {
            $fileName = Str::random(3) . '_' . $image->getClientOriginalName();
            $image->move(public_path() . '/images', $fileName);
        }
    }
}
