<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\SettingRequest;
use App\Models\KeyValue;
use Laravelcrud\Crud\Http\Controllers\CrudController;

class SettingController extends CrudController
{
    /**
     * MapController constructor.
     * @param KeyValue $model
     */
    public function __construct(KeyValue $model)
    {
        view()->share('title', 'Setting');
        $this->model = $model;
    }

    /**
     * Store a newly created resource in storage.
     * POST /media
     *
     * @param SettingRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SettingRequest $request)
    {
        return $this->model->createObject($request);
    }

    /**
     * Update the specified resource in storage.
     * PUT /pages/{id}
     *
     * @param SettingRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SettingRequest $request, $id)
    {
        $request->request->remove('item_key');
        return $this->model->updateObject($id, $request);
    }
}
