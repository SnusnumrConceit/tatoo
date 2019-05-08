<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MasterTatoo extends Model
{
    protected $fillable = ['employee_id', 'tatoo_id'];
    public $timestamps = false;
}
