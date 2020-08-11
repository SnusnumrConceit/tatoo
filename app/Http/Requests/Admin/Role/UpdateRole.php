<?php

namespace App\Http\Requests\Admin\Role;

use Illuminate\Validation\Rule;

class UpdateRole extends StoreRole
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
            'name' => [
                'required',
                'string',
                'alpha_dash',
                'between:5,30',
                Rule::unique('roles', 'name')->ignore($this->role->id)
            ],
            'slug' => [
                'bail',
                'required',
                'string',
                'alpha_dash',
                'between:5,40',
                Rule::unique('roles', 'slug')->ignore($this->role->id)
            ]
        ];
    }
}
