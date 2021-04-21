<?php

namespace App\Http\Requests\Admin;

use App\Models\Article;
use App\Models\Page;
use Laravelcrud\Crud\Http\Rquests\CrudRequest;

class ArticleRequest extends CrudRequest
{
    /**
     * Model for name attributes building
     *
     * @var string
     */
    protected $model = Article::class;

    /**
     * Get the validator instance for the request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function getValidatorInstance() {
        $newsPage = Page::newsPage();
        // Adding type before validation
        $this->request->add(['parent_id' => $newsPage->id]);
        return parent::getValidatorInstance();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = $this->getLanguageRules([
            'title' => 'required|string|min:2|max:255',
            'content' => 'required|string|min:2',
        ]);
        $rules['image.original'] = '';
        $rules['image.cropped'] = 'required_with:image.original';
        $rules['viewed'] = 'int|min:0';
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
        $attributes['image.original'] = 'Image';
        $attributes['image.cropped'] = 'Cropped image';
        return $attributes;
    }
}
