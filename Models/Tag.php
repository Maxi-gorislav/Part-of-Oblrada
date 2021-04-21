<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravelcrud\Crud\Crud;

class Tag extends Model
{
    use Crud;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
    ];

    /**
     * The attributes for CRUD methods
     *
     * @var array
     */
    public $fields = [
        'title' => [
            'type' => 'string',
        ],
    ];

    /**
     * The attributes for CRUD methods
     *
     * @var array
     */
    protected $columns = ['title'];

    /**
     * The attributes for CRUD methods
     *
     * @var array
     */
    protected $searchable = ['title'];

    public function getViewableAttribute()
    {
        return false;
    }

    public function getEditableAttribute()
    {
        return false;
    }

    public function getDeletableAttribute()
    {
        return true;
    }

//    public function deleteObject($id, Array $cascadeRelations = [], Array $noActionsRelations = [])
//    {
//
//    }
}
