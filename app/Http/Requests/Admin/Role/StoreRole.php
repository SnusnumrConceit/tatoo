<?php

namespace App\Http\Requests\Admin\Role;

use Illuminate\Foundation\Http\FormRequest;

class StoreRole extends FormRequest
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
            'name' => 'required|string|alpha_dash|between:5,30|unique:roles,name',
            'slug' => 'bail|required|string|alpha_dash|between:5,40|unique:roles,slug'
        ];
    }
    
    public function attributes()
    {
        return [
            'name' => __('roles.name'),
            'slug' => __('roles.slug')
        ];
    }
}
