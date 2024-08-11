<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Galaxy extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description'];

    public function bodies(): HasMany
    {
        return $this->hasMany(Body::class);
    }
}
