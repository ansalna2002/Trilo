<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::create([
            'email'         => 'ansalnaans2002@gmail.com',
            'phone_number'  => '9999999999',
            'user_id'       => 'ADM001',
            'name'          => 'Admin User',
            'role'          => 'admin',
            'otp'           => null,
            'otp_verified'  => true,
            'is_active'     => true,
            'dob'           => '1990-01-01',
            'gender'        => 'male',
            'interest'      => null,
            'country'       => 'USA',
            'language'      => 'English',
            'profile_image' => null,
            'password'      => Hash::make('123456'),
        ]);
            
    }
}
