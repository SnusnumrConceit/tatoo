<?php

namespace App\Http\Requests\Admin\Appointment;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointment extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'name' => 'required|string|between:5,30|unique:appointments,name'
		];
	}
	
	/**
	 * Get the validation attributes
	 *
	 * @return array
	 */
	public function attributes()
	{
		return [
			'name' => 'Наименование должности'
		];
	}
}
