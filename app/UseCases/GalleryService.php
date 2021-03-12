<?php

namespace App\UseCases;

use File;
use Illuminate\Support\Facades\Storage;
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
            $fileName = Str::random(3) . '_' . $this->rus2translit($image->getClientOriginalName());
            $image->move(public_path() . '/images', $fileName);
        }
    }

    public function deleteImage(string $fileName): void
    {
        if (Storage::disk('public_images')->exists($fileName)) {
            Storage::disk('public_images')->delete($fileName);
        }
    }

    private function rus2translit($text)
    {
        $cyr = [
            'а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п',
            'р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я',
            'А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П',
            'Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я', ' '
        ];
        $lat = [
            'a','b','v','g','d','e','io','zh','z','i','y','k','l','m','n','o','p',
            'r','s','t','u','f','h','ts','ch','sh','sht','a','i','y','e','yu','ya',
            'A','B','V','G','D','E','Io','Zh','Z','I','Y','K','L','M','N','O','P',
            'R','S','T','U','F','H','Ts','Ch','Sh','Sht','A','I','Y','e','Yu','Ya', '_'
        ];

        return str_replace($cyr, $lat, $text);
    }
}
