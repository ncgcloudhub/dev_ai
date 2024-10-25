<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationTools extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    public function favorites()
    {
        return $this->hasMany(EducationToolsFavorite::class, 'tools_id');
    }

    public function getIsFavoritedAttribute()
    {
        $user = auth()->user(); // Get the currently authenticated user
        return $user ? $this->favorites()->where('user_id', $user->id)->exists() : false;
    }
}
