<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UserFormRequest extends FormRequest
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
            'email'         =>  'required|email|min:10|max:60',
            'password'      =>  'required|min:8|max:50',
            'first_name'    =>  'required|min:3|max:20',
            'last_name'     =>  'required|min:2|max:30',
            'birthday'      =>  'required|date',
            'role'          =>  'required'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, response()->json([
            'status'    =>  'error',
            'errors'    =>  $validator->errors(),
            'msg'       =>  'Проверьте корректность данных'
        ]));
    }

    public function failedAuthorization()
    {
        throw new AuthorizationException('Вы не авторизованы', 403);
    }
}
