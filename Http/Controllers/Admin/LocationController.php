<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\LocationRequest;
use App\Models\Location;
use Laravelcrud\Crud\Http\Controllers\CrudController;

class LocationController extends CrudController
{
    /**
     * MapController constructor.
     * @param Location $model
     */
    public function __construct(Location $model)
    {
        view()->share('title', 'Locations');
        $this->model = $model;
    }

    public function edit($id)
    {
        return parent::edit($id)->with('js', view('admin.location-js')->render());
    }

    /**
     * Update the specified resource in storage.
     * PUT /pages/{id}
     *
     * @param LocationRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(LocationRequest $request, $id)
    {
        return $this->model->updateObject($id, $request);
    }
}
