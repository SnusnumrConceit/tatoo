<?php

namespace App\Http\Requests\Admin\Appointment;

use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class DeleteAppointment extends FormRequest
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
		return [];
	}
	
	/**
	 * Processing data validation
	 *
	 * @param Validator $validator
	 * @throws \Illuminate\Validation\ValidationException
	 */
	public function withValidator(Validator $validator)
	{
		$errors = $validator->errors();
		
		if ($this->appointment->hasEmployees()) {
			$errors->add('appointment', __('appointments.errors.is_used'));
		}
		
		if ($errors->isNotEmpty()) {
			$this->failedValidation($validator);
		}
	}
}
