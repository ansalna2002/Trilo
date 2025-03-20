<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanSubscription extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'coins',
        'amount',
        'subscribed_date',
        'status',
        'is_subscribed',
    ];

    /**
     * Get the user that owns the subscription.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
