<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Laravelcrud\Crud\Http\Rquests\CrudRequest;

class UserRequest extends CrudRequest
{
    /**
     * Model for name attributes building
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'first_name' => 'required|string|min:2|max:32',
            'last_name' => 'required|string|min:2|max:32',
            'email' => 'required|email|unique:users',
        ];

        // The string_password field  isn`t required
        if($this->input('string_password')) {
            $rules['string_password'] = 'string|min:6';
        }

        // The role field  is required if checked admin field
        if($this->has('admin')) {
            $rules['role'] = 'required';
        }

        // Change validation for update
        if($this->method() == 'PUT') {
            $rules['email'] = 'required|email|unique:users,email,'.$this->route()->parameter('user');
        }

        return $rules;
    }
}
