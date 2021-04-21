<?php

namespace App\Http\Requests\Admin;

use App\Models\Category;
use Laravelcrud\Crud\Http\Rquests\CrudRequest;

class CategoryRequest extends CrudRequest
{
    /**
     * Model for name attributes building
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->getLanguageRules([
            'title' => 'required|string|min:2|max:32',
        ]);
    }
}
