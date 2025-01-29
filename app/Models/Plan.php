<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'user_id',
        'plan',
        'talk_time',
        'type',
        'amount',
        'available_days',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
