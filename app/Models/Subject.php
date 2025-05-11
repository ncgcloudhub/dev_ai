<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function gradeClass()
    {
        return $this->belongsTo(GradeClass::class, 'grade_id');
    }
    
    public function educationContents()
    {
        return $this->hasMany(EducationContent::class);
    }

}
