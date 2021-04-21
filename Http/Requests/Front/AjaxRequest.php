<?php

namespace App\Http\Requests\Front;

use Illuminate\Foundation\Http\FormRequest;

class AjaxRequest extends FormRequest
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
        switch ($this->route()->getName()) {
            case 'front.ajax.subscribe':
                $rules =  [
                    'email' => 'required|email|unique:subscribers,email|max:255',
                ];
                break;
            case 'front.ajax.contact':
                $rules =  [
                    'full_name' => 'required|string',
                    'email' => 'required|email',
                    'message' => 'required|string',
                ];
                break;
        }
        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'email' => 'Електронна пошта',
        ];
    }
}
