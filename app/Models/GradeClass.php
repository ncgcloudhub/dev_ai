<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeClass extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'grade_id'); // or belongsToMany, depending on your structure
    }
    
    public function educationContents()
    {
        return $this->hasMany(educationContent::class, 'grade_id');
    }
    
}
