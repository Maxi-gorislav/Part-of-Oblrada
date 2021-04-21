<?php

namespace App\Models;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Laravelcrud\Crud\Crud;

class Category extends Model
{
    use Crud, Translatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'color'
    ];

    public $translatedAttributes = [
        'title', 'description'
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
     protected $with = ['translations'];

    /**
     * The attributes for CRUD methods
     *
     * @var array
     */
    public $fields = [
        'title' => [
            'type' => 'string',
        ],
        'description' => [
            'type' => 'text',
        ],
        'color' => [
            'type' => 'color',
            'show_mutator' => 'showColor',
        ]
    ];

    /**
     * The attributes for CRUD methods
     *
     * @var array
     */
    protected $columns = ['title', 'description'];

    /**
     * The attributes for CRUD methods
     *
     * @var array
     */
    protected $searchable = ['title', 'description'];

    public function showColor() {
        return '<span class="badge" style="
            color: ' . $this->color . '; 
            background-color: rgba(' . hex2RGB($this->color, true, ', ') . ', 0.2);
            border-radius: 3px;
            padding: 0 6px;
            font-size: 12px;
            line-height: 20px;
            text-transform: uppercase;
            ">' . $this->title . '</span>';
    }
}
