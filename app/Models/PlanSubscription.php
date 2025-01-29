<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanSubscription extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'plan_id',
        'plan_name',
        'amount',
        'type',
        'available_days',
        'subscribed_date',
        'talk_time',
        'status',
        'is_subscribed',
        'remark'
    ];

    /**
     * Get the user that owns the subscription.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
