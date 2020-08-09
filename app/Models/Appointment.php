<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
	protected $fillable = ['name'];

	public $timestamps = false;
	
	/**
	 * Employees O-2-M relation
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function employees()
	{
		return $this->hasMany(Employee::class, 'appointment_id', 'id');
	}
	
	/**
	 * Check existing employees
	 *
	 * @return bool
	 */
	public function hasEmployees()
	{
		return $this->employees()->exists();
	}
}
