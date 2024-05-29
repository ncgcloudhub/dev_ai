<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromptLibraryCategory extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function prompts()
    {
        return $this->hasMany(PromptLibrary::class, 'category_id', 'id');
    }

    public function subcategories()
    {
        return $this->hasMany(PromptLibrarySubCategory::class, 'category_id', 'id');
    }

}
