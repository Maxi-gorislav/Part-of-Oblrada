<?php

namespace App\Http\Requests\Admin;

use App\Models\Map;
use Laravelcrud\Crud\Http\Rquests\CrudRequest;

class MapRequest extends CrudRequest
{
    /**
     * Model for name attributes building
     *
     * @var string
     */
    protected $model = Map::class;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = $this->getLanguageRules([
            'title' => 'required|string|min:2|max:255',
        ]);
        $rules['image'] = 'required';
        return $rules;
    }
}
