<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageHistory extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    public function package()
    {
        return $this->belongsTo(PricingPlan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


}
