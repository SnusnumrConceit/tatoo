<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email'     =>  'required|email|min:10|max:60',
            'password'  =>  'required|min:8|max:50'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        return response()->json([
            'status'    =>  'error',
            'errors'    =>  $validator->errors(),
            'msg'       =>  'Проверьте корректность данных'
        ], 200);
    }

    public function failedAuthorization()
    {
        throw new AuthorizationException('Вы не авторизованы', 403);
    }
}
