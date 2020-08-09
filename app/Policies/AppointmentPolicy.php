<?php

namespace App\Policies;

use App\User;
use App\Models\Appointment;
use Illuminate\Auth\Access\HandlesAuthorization;

class AppointmentPolicy
{
	use HandlesAuthorization;
	
	/**
	 * Determine whether the user can view the appointment.
	 *
	 * @param  \App\User  $user
	 * @return mixed
	 */
	public function index(User $user)
	{
		return $user->hasPermission('appointments_view');
	}

	/**
	 * Determine whether the user can view the appointment.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Models\Appointment  $appointment
	 * @return mixed
	 */
	public function view(User $user, Appointment $appointment)
	{
		return $user->hasPermission('appointments_view');
	}

	/**
	 * Determine whether the user can create appointments.
	 *
	 * @param  \App\User  $user
	 * @return mixed
	 */
	public function create(User $user)
	{
		return $user->hasPermission('appointments_create');
	}

	/**
	 * Determine whether the user can update the appointment.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Models\Appointment  $appointment
	 * @return mixed
	 */
	public function update(User $user, Appointment $appointment)
	{
		return $user->hasPermission('appointments_edit');
	}

	/**
	 * Determine whether the user can delete the appointment.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Models\Appointment  $appointment
	 * @return mixed
	 */
	public function delete(User $user, Appointment $appointment)
	{
		return $user->hasPermission('appointments_delete');
	}
}
