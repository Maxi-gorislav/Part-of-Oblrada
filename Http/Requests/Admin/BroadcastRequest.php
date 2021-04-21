<?php

namespace App\Http\Requests\Admin;

use App\Models\Article;
use Laravelcrud\Crud\Http\Rquests\CrudRequest;

class BroadcastRequest extends CrudRequest
{
    /**
     * Model for name attributes building
     *
     * @var string
     */
    protected $model = Article::class;

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
    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes['image'] = 'Resource url';
        return $attributes;
    }
}
