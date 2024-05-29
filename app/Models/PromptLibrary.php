<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromptLibrary extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(PromptLibraryCategory::class, 'category_id', 'id');
    }
    public function subcategory()
    {
        return $this->belongsTo(PromptLibrarySubCategory::class, 'sub_category_id', 'id');
    }
}
