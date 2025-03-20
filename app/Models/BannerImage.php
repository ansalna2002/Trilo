<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerImage extends Model
{
    protected $fillable = [ 
        'banner_name',
        'banner_image',
        'one',
        'two',
        'three',
    ];
}
