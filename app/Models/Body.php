<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Body extends Model
{
    use HasFactory;

        protected $fillable = ['title', 'type', 'description', 'image_path', 'galaxy_id'];

    public function galaxy(): BelongsTo
    {
        return $this->belongsTo(Galaxy::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
