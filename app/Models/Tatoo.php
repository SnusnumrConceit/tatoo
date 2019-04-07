<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tatoo extends Model
{
    protected $fillable = [
        'url', 'name', 'description', 'price'
    ];

    public $timestamps = false;
}
