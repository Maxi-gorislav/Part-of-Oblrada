<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\RoleRequest;
use App\Models\Role;
use Laravelcrud\Crud\Http\Controllers\CrudController;
use Spatie\Permission\PermissionRegistrar;

class RoleController extends CrudController
{
    /**
     * RoleController constructor.
     * @param Role $model
     */
    public function __construct(Role $model)
    {
        parent::__construct();
        view()->share('title', 'Roles');
        $this->model = $model;
    }


    /**
     * Store a newly created resource in storage.
     * POST /pages
     *
     * @param RoleRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(RoleRequest $request)
    {
        $permissions = $request->input('permissions', []);
        $request->replace($request->except('permissions'));
        return $this->model->createObject($request, compact('permissions'));
    }

    /**
     * Update the specified resource in storage.
     * PUT /pages/{id}
     *
     * @param RoleRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(RoleRequest $request, $id)
    {
        $permissions = $request->input('permissions', []);
        $result = $this->model->updateObject($id, $request, compact('permissions'));
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        return $result;
    }
}
