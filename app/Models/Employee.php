<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = ['name', 'description', 'birthday', 'appointment_id', 'url'];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function tatoos()
    {
        return $this->belongsToMany(
            Tatoo::class,
            'master_tatoos',
            'employee_id',
            'tatoo_id');
    }

    public function sortByAppointment($type)
    {
        return $this->join('appointments', 'employees.appointment_id', '=', 'appointments.id')
            ->orderBy('appointments.name', $type)
            ->select('employees.*');
    }
}
