<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function referralUser()
    {
        return $this->belongsTo(User::class, 'referral_id');
    }
}
