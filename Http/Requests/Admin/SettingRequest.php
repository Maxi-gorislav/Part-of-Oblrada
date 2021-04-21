<?php

namespace App\Http\Requests\Admin;

use App\Models\KeyValue;
use Laravelcrud\Crud\Http\Rquests\CrudRequest;

class SettingRequest extends CrudRequest
{
    /**
     * Model for name attributes building
     *
     * @var string
     */
    protected $model = KeyValue::class;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'item_key' => 'required',
            'item_value' => 'required',
        ];
    }
}
