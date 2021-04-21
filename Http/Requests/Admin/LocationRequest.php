<?php

namespace App\Http\Requests\Admin;

use App\Models\Location;
use Laravelcrud\Crud\Http\Rquests\CrudRequest;

class LocationRequest extends CrudRequest
{
    /**
     * Model for name attributes building
     *
     * @var string
     */
    protected $model = Location::class;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = $this->getLanguageRules([
//            'title' => 'required|string|min:2|max:255',
//            'content' => 'required|string|min:2',
        ]);
        return $rules;
    }
}
