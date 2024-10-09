<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'location', 'description', 'category', 'all_day', 'start', 'end', 's_time', 'e_time'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
