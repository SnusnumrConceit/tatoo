<?php

namespace App\Http\Requests\Admin\Employee;

use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class EmployeeFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        /*return auth()->user()->hasRole('superadmin|admin');*/
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
            'name'          =>  'required|min:3|max:20',
            'description'   =>  'required|min:5|max:500',
            'birthday'      =>  'required|date',
            'appointment_id'=>  'required|integer'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, response()->json([
            'status'    =>  'error',
            'msg'       =>  'Проверьте корректность данных',
            'errors'    =>  $validator->errors()
        ]));
    }

    public function failedAuthorization()
    {
        throw new AuthorizationException('Вы не авторизованы', 403);
    }
}
