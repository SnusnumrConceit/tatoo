<?php

namespace App\Http\Requests\Admin\Role;

use Illuminate\Foundation\Http\FormRequest;

class DestroyRole extends FormRequest
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
            'name' => 'required|alpha_dash|string|between:5,30|unique:users,role_name',
        ];
    }
    
    public function messages()
    {
        return [
            'name.unique' => __('roles.errors.is_used')
        ];
    }
    
    public function attributes()
    {
        return [
            'name' => __('roles.name')
        ];
    }
}
