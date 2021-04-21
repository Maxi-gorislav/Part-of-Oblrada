<?php

namespace App\Models;

use Laravelcrud\Crud\Crud;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role as Model;

class Role extends Model
{
    use Crud;

    /**
     * The attributes for CRUD methods
     *
     * @var array
     */
    public $fields = [
        'name' => [
            'type' => 'string',
        ],
        'permissions' => [
            'type' => 'custom',
            'view' => 'admin.permissions',
            'data_mutator' => 'getPermissions',
            'show_mutator' => 'getPermissionsShow',
        ]
    ];

    /**
     * The attributes for CRUD methods
     *
     * @var array
     */
    protected $columns = ['name'];

    /**
     * The attributes for CRUD methods
     *
     * @var array
     */
    protected $searchable = ['name'];

    public function getPermissions() {
        return Permission::all();
    }

    public function getPermissionsShow() {
        return view('admin.permissions_show', [
            'data' => $this->getPermissions(),
            'object' => $this,
        ]);
    }

    public function getSelectedPermissionsAttribute() {
        return array_column($this->permissions->toArray(), 'id');
    }

    public function getDeletableAttribute() {
        return $this->getAttribute('name') !== 'Super admin';
    }

    public function getEditableAttribute() {
        return $this->getAttribute('name') !== 'Super admin';
    }
}
