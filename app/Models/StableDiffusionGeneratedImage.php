<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StableDiffusionGeneratedImage extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function StableDiffusionlikes()
    {
        return $this->hasMany(StableDiffusionImageLike::class);
    }

}
