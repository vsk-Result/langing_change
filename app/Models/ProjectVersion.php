<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProjectVersion extends Model
{
    protected $table = 'project_versions';

    protected $fillable = ['project_id', 'title', 'storage_url'];

//    protected $touches = ['project'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function getHTMLPath(): string
    {
        return Storage::url($this->storage_url . 'index.html');
    }

    public function getArchivePath(): string
    {
        return $this->storage_url . 'landing.zip';
    }

    public function getArchiveStoragePath(): string
    {
        return storage_path('app/public/' . $this->storage_url . 'landing.zip');
    }
}
