<?php

namespace App\Http\Requests\Admin\Appointment;

use Illuminate\Validation\Rule;

class UpdateAppointment extends StoreAppointment
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'name' => [
				'required',
				'string',
				'between:5,30',
				Rule::unique('appointments', 'name')->ignore($this->appointment->id)
			]
		];
	}
}
