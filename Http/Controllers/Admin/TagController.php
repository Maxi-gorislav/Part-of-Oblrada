<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use Laravelcrud\Crud\Http\Controllers\CrudController;

class TagController extends CrudController
{
    /**
     * TagController constructor.
     * @param Tag $model
     */
    public function __construct(Tag $model)
    {
        parent::__construct();
        view()->share('title', 'Tags');
        $this->model = $model;
    }
}
