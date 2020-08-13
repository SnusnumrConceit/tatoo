<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Validation\Rule;

class UpdateUser extends StoreUser
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
            'email'         =>  [
                'required',
                'email',
                'between:10,60',
                Rule::unique('users', 'email')->ignore($this->user->id)
            ],
            'first_name'    =>  'required|string|between:3,20',
            'last_name'     =>  'required|string|between:2,30',
            'birthday'      =>  'required|date',
            'role_name'     =>  'required|exists:roles,name'
        ];
    }
}
