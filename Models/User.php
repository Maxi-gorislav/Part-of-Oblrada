<?php

namespace App\Models;

use App\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravelcrud\Crud\Crud;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, Crud, HasRoles;

    protected $guard_name = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'admin', 'active', 'image'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes for CRUD methods
     *
     * @var array
     */
    public $fields = [
        'first_name' => [
            'type' => 'string',
        ],
        'last_name' => [
            'type' => 'string',
        ],
        'email' => [
            'type' => 'string',
        ],
        'string_password' => [
            'type' => 'string',
            'label' => 'Password',
            'show_view' => false,
        ],
        'image' => [
            'type' => 'file',
            'aspect_ratio' => 1,
            'show_mutator' => 'getImage',
            'column_mutator' => 'getSmallImage'
        ],
        'admin' => [
            'type' => 'bool',
        ],
        'active' => [
            'type' => 'bool',
        ],
        'role' => [
            'selector' => 'getRoles',
            'type' => 'select',
        ],
    ];

    /**
     * The attributes for CRUD methods
     *
     * @var array
     */
    protected $columns = ['first_name', 'last_name', 'email', 'image', 'active'];

    /**
     * The attributes for CRUD methods
     *
     * @var array
     */
    protected $searchable = ['first_name', 'last_name', 'email'];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token, $this->first_name));
    }

    /**
     * Get preview for image
     *
     * @return string
     */
    public function getImage() {
        return $this->image ? '<img width="200" class="img" src="'. storage_url($this->image).'">' : '';
    }

    /**
     * Get small preview for image
     *
     * @return string
     */
    public function getSmallImage() {
        return $this->image ? '<img width="50" class="img" src="'. storage_url(thumb($this->image)).'">' : '';
    }

    public function getRoles() {
        return Role::all()->map(function ($role) {
            return [
                'label' => $role->name,
                'value' => $role->name,
            ];
        })->prepend([
            'label' => '---',
            'value' => '',
        ]);
    }

    public function getRoleAttribute() {
        $role = $this->roles()->first();
        return $role ? $role->name : '';
    }
}
