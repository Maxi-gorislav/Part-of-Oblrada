<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\PageRequest;
use App\Models\Menu;
use App\Models\Page;
use Laravelcrud\Crud\Http\Controllers\CrudController;

class PageController extends CrudController
{
    protected $menu;
    /**
     * PageController constructor.
     * @param Page $model
     * @param Menu $menu
     */
    public function __construct(Page $model, Menu $menu)
    {
        parent::__construct();
        view()->share('title', 'Pages');
        $this->model = $model;
        $this->menu = $menu;
    }

    /**
     *
     * Display a listing of the resource.
     * GET /articles
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Laravelcrud\Crud\Http\Controllers\Response
     */
    public function index()
    {
        return $this->model->getList(['system' => 0]);
    }

    /**
     * Store a newly created resource in storage.
     * POST /pages
     *
     * @param PageRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PageRequest $request)
    {
        article_correction($request);
        $result = $this->model->createObject($request);
        $this->menu->forgetCache();
        return $result;
    }

    /**
     * Update the specified resource in storage.
     * PUT /pages/{id}
     *
     * @param PageRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PageRequest $request, $id)
    {
        article_correction($request);
        $request->request->remove('alias');
        return $this->model->updateObject($id, $request);
    }

    /**
     * Delete page
     * DELETE /pages/{id}
     *
     * @param int $id
     * @return \Laravelcrud\Crud\Http\Controllers\Response
     */
    public function destroy($id)
    {
        $result = parent::destroy($id);
        $this->menu->forgetCache();
        return $result;
    }
}
