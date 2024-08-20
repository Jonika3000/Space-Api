<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @OA\Schema(
 *     schema="Galaxy",
 *     type="object",
 *     @OA\Property(property="id", type="integer", format="int64"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="description", type="string")
 * )
 */
class Galaxy extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description'];

    public function bodies(): HasMany
    {
        return $this->hasMany(Body::class);
    }
}
