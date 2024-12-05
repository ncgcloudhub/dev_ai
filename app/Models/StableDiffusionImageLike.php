<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StableDiffusionImageLike extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function image()
    {
        return $this->belongsTo(StableDiffusionGeneratedImage::class);
    }

    public function images()
    {
        return $this->belongsTo(StableDiffusionGeneratedImage::class, 'image_id');
    }


}
