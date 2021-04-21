<?php

namespace App\Http\Requests\Admin;

use App\Models\Gallery;
use App\Models\Media;
use App\Models\Page;
use Laravelcrud\Crud\Http\Rquests\CrudRequest;

class GalleryRequest extends CrudRequest
{
    /**
     * Model for name attributes building
     *
     * @var string
     */
    protected $model = Gallery::class;

    /**
     * Additional attributes for media items
     *
     * @var array
     */
    private $additional_attributes = [];

    /**
     * Get the validator instance for the request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function getValidatorInstance() {
        $galleryPage = Page::galleryPage();
        // Adding type before validation
        $this->request->add(['type' => 'photo-gallery', 'parent_id' => $galleryPage->id]);
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
        ]);
        $rules['image.cropped'] = 'required';
        $rules['image.original'] = 'required';

        $media = new MediaRequest();
        $media_rules = $media->rules();
        $media_attributes = $media->attributes();
//        dd($media_attributes, $media_rules);

        foreach ($media_rules as $name => $rule) {
            $rules['mediaItems.*.' . $name] = $rule;
            $this->additional_attributes['mediaItems.*.' . $name] = "Item ".$media_attributes[$name];
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
        $attributes = parent::attributes();
        $attributes['image.original'] = 'Image';
        $attributes['image.cropped'] = 'Cropped image';
        return array_merge($attributes, $this->additional_attributes);
    }
}