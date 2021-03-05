<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Landing extends Model
{
    protected $table = 'landings';

    protected $fillable = ['title', 'preview_url', 'storage_url'];

    public function getPreviewUrl()
    {
        return asset($this->preview_url);
    }
}
