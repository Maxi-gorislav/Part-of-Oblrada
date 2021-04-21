<?php

namespace App\Http\Requests\Admin;

use App\Models\Media;
use Laravelcrud\Crud\Http\Rquests\CrudRequest;

class MediaRequest extends CrudRequest
{
    /**
     * Model for name attributes building
     *
     * @var string
     */
    protected $model = Media::class;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
//        $rules = $this->getLanguageRules([
//            'title' => 'string|min:2|max:255',
//        ]);
        $rules['path.cropped'] = 'required';
        $rules['path.original'] = 'required';
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
        $attributes['path.original'] = 'Image';
        $attributes['path.cropped'] = 'Cropped image';
        return $attributes;
    }
}