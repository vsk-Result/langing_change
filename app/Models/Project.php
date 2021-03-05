<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $table = 'projects';

    protected $fillable = ['user_id', 'landing_id', 'actual_version_id', 'title', 'description'];

    public function landing(): BelongsTo
    {
        return $this->belongsTo(Landing::class, 'landing_id');
    }

    public function actualVersion(): BelongsTo
    {
        return $this->belongsTo(ProjectVersion::class, 'actual_version_id');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(ProjectVersion::class, 'project_id');
    }

    public static function getByCurrentUser()
    {
        return self::where('user_id', auth()->id())->latest()->get();
    }
}
