<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * @OA\Schema(
 *     schema="Post",
 *     type="object",
 *     @OA\Property(property="id", type="integer", format="int64"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="user_id", type="integer"),
 *     @OA\Property(property="body_id", type="integer"),
 * )
 */
class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'user_id', 'body_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function body(): BelongsTo
    {
        return $this->belongsTo(Body::class);
    }
}
