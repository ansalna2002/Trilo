<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddBank extends Model
{
    protected $fillable = [
        'user_id',
        'account_holder_name',
        'account_number',
        'ifsc_code',
        'bank_name',
        'pan_number',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
