<?php

namespace App\Http\Requests\Admin;

use App\Models\Video;
use Laravelcrud\Crud\Http\Rquests\CrudRequest;

class VideoRequest extends CrudRequest
{
    /**
     * Model for name attributes building
     *
     * @var string
     */
    protected $model = Video::class;

    /**
     * Get the validator instance for the request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function getValidatorInstance() {
        // Adding type before validation
        $this->request->add(['type' => 'video']);
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
        $rules['url'] = ['required', 'url', 'regex:/http(?:s?):\/\/(?:www\.)?youtube(\.com\/watch\?v=)([\w\-\_]*)/'];
        return $rules;
    }
}
