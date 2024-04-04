<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomTemplate extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function template_category(){
    	return $this->belongsTo(TemplateCategory::class,'category_id','id');
    }
}
