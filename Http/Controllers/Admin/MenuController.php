<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\MenuRequest;
use App\Models\Menu;
use Laravelcrud\Crud\Http\Controllers\CrudController;

class MenuController extends CrudController
{
    /**
     * MenuController constructor.
     * @param Menu $model
     */
    public function __construct(Menu $model)
    {
        parent::__construct();
        view()->share('title', 'Menus');
        $this->model = $model;
    }

    /**
     * Store a newly created resource in storage.
     * POST /menus
     *
     * @param MenuRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(MenuRequest $request)
    {
        $items = $request->input('pages', []);
        return $this->model->createObject($request, compact('items'));
    }

    /**
     * Update the specified resource in storage.
     * PUT /menus/{id}
     *
     * @param MenuRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(MenuRequest $request, $id)
    {
        $menu = $this->model->find($id);
        if($menu->name === Menu::HOME_MENU) {
            $fields = $request->all();
            $fields['title'] = Menu::HOME_MENU;
            $request->replace($fields);
        }
        $items = $request->input('pages', []);
        return $this->model->updateObject($id, $request, compact('items'));
    }
}
