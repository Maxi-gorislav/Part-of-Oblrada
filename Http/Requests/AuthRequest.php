<?php

namespace App\Http\Requests;

class AuthRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        switch ($this->route()->getName()) {
            case 'login':
            case 'api.login':
                $rules = [
                    'email' => 'required|email',
                    'password' => 'required|string',
                ];
                break;
            case 'password.email':
            case 'api.password.email':
                $rules = [
                    'email' => 'required|email'
                ];
                break;
            case 'register':
            case 'api.register':
                $rules = [
                    'first_name' => 'required|string|max:255',
                    'last_name' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255|unique:users',
                    'password' => 'required|string|min:6|confirmed',
                ];
                break;
            case 'password.request':
            case 'api.password.request':
                $rules = [
                    'token' => 'required',
                    'email' => 'required|email',
                    'password' => 'required|confirmed|min:6',
                ];
                break;
        }

        return $rules;
    }
}
