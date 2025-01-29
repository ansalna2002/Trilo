<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $fillable = [
        'crnt_user_id',
        'folw_user_id',
        'is_active',
    ];
     // Define the relationship to the followed user
     public function followedUser()
     {
         return $this->belongsTo(User::class, 'folw_user_id', 'user_id');
     }
}
