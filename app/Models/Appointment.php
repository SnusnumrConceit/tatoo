<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = ['name'];

    public $timestamps = false;

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'id', 'appointment_id');
    }
}
