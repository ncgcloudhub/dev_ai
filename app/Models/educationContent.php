<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class educationContent extends Model
{
    use HasFactory;
    protected $guarded = [];

        // Define the relationship with GradeClass
        public function gradeClass()
        {
            return $this->belongsTo(GradeClass::class, 'grade_id');
        }

        public function gradeClasss()
        {
            return $this->belongsTo(GradeClass::class, 'grade_id');
        }

    
        // Define the relationship with Subject
        public function subject()
        {
            return $this->belongsTo(Subject::class, 'subject_id');
        }
    
        // Define the relationship with User
        public function user()
        {
            return $this->belongsTo(User::class, 'user_id');
        }
}
