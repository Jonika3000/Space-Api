<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @OA\Schema(
 *     schema="Body",
 *     type="object",
 *     @OA\Property(property="id", type="integer", format="int64"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="image_path", type="string"),
 *     @OA\Property(property="galaxy_id", type="integer"),
 * )
 */
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
