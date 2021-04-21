<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use Laravelcrud\Crud\Http\Controllers\CrudController;
use DB;

class UserController extends CrudController
{
    /**
     * UserController constructor.
     * @param User $model
     */
    public function __construct(User $model)
    {
        parent::__construct();
        view()->share('title', 'Users');
        $this->model = $model;
    }

    /**
     * Store a newly created resource in storage.
     * POST /users
     *
     * @param UserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserRequest $request)
    {
        $fields = $request->except(['string_password', 'role']);
        if($request->input('string_password')) {
            $fields['password'] = bcrypt($request->input('string_password'));
        }
        if($request->input('role')) {
            $role = $request->input('role');
        }
        $request->replace($fields);
        $result = $this->model->createObject($request);
        if(isset($role)) {
            $user = $this->model->getObject();
            $user->assignRole($role);
        }
        return $result;
    }

    /**
     * Update the specified resource in storage.
     * PUT /users/{id}
     *
     * @param UserRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserRequest $request, $id)
    {

        $fields = $request->except(['string_password', 'role']);
        if($request->input('string_password')) {
            $fields['password'] = bcrypt($request->input('string_password'));
        }
        if($request->input('role')) {
            $role = $request->input('role');
        }
        $request->replace($fields);
        DB::beginTransaction();
        try {
            if(isset($fields['password'])) {
                $this->model->where('id', $id)->update(['password' => $fields['password']]);
            }
            $result = $this->model->updateObject($id, $request);
            if(isset($role)) {
                $user = $this->model->find($id);
                $user->roles()->detach();
                $user->assignRole($role);
            }
            DB::commit();
            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            $error = $this->model->errorMessage($e);
            return redirect()->back()
                ->with('error', $error);
        }
    }
}
