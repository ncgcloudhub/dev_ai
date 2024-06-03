<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromptExample extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function prompt()
    {
        return $this->belongsTo(PromptLibrary::class);
    }
    
}
