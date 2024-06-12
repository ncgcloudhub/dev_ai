<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DalleImageGenerate extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user(){
    	return $this->belongsTo(User::class,'user_id','id');
    }

    public function likes()
    {
        return $this->hasMany(LikedImagesDalle::class, 'image_id');
    }
    
}
