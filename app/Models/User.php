<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{

    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $fillable = [
        'phone_number',
        'user_id',
        'name',
        'role',
        'otp',
        'otp_verified',
        'otp_expires_at',
        'is_active',
        'dob',
        'gender',
        'interest',
        'country',
        'language',
        'about',
        'profile_image',
        'email',
        'password',
        'last_login',
        'type',
        'is_subscriber',
        'subscribed_date'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    // Define the relationship for users the current user is following
    public function following()
    {
        return $this->hasMany(Follow::class, 'crnt_user_id', 'id');
    }

    // Define the relationship for users who follow the current user
    public function followers()
    {
        return $this->hasMany(Follow::class, 'folw_user_id', 'id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function isAdmin()
    {
        return $this->role === 'admin'; 
    }
    
    public function isOnline()
    {
        if ($this->last_login) {
            $now = Carbon::now();
            return $this->last_login->diffInMinutes($now) <= 3;
        }
        return false;
    }
}
