<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostImage extends Model
{
    use HasFactory;

    protected $fillable = ['post_id', 'image_id'];

    protected function post():BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    protected function image():BelongsTo
    {
        return $this->belongsTo(Image::class);
    }
}
