<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use Laravelcrud\Crud\Http\Controllers\CrudController;

class CategoryController extends CrudController
{
    /**
     * CategoryController constructor.
     * @param Category $model
     */
    public function __construct(Category $model)
    {
        parent::__construct();
        view()->share('title', 'Categories');
        $this->model = $model;
    }


    /**
     * Store a newly created resource in storage.
     * POST /categories
     *
     * @param CategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CategoryRequest $request)
    {
        return $this->model->createObject($request);
    }

    /**
     * Update the specified resource in storage.
     * PUT /categories/{id}
     *
     * @param CategoryRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CategoryRequest $request, $id)
    {
        return $this->model->updateObject($id, $request);
    }
}
