<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'name',
        'user_id',
        'number',
        'plan_id',
        'transaction_id',
        'amount',
        'status',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
