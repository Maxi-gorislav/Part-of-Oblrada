<?php

namespace App\Http\Requests\Admin;

use App\Models\Menu;
use Laravelcrud\Crud\Http\Rquests\CrudRequest;

class MenuRequest extends CrudRequest
{
    /**
     * Model for name attributes building
     *
     * @var string
     */
    protected $model = Menu::class;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules['title'] = 'required|max:100';
        return $rules;
    }
}
