<?php

namespace App\Models;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Laravelcrud\Crud\Crud;

class Map extends Model
{
    use Crud, Translatable;

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['translations'];

    protected $fillable = ['title', 'image'];
    /**
     * @var array
     */
    public $translatedAttributes = [
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
        'image' => [
            'type' => 'file',
            'aspect_ratio' => 1,
            'crop' => false,
            'show_mutator' => 'getImage',
            'column_mutator' => 'getImage'
        ],
        'path' => [
            'label' => 'Excel file',
            'type' => 'string',
            'show_view' => false,
            'help' => 'You can download the <a href="/xlsx/example.xlsx">template file</a>',
        ],
        'locations' => [
            'type' => 'hidden',
            'show_view' => true,
            'show_relation' => 'locations',
        ]
    ];

    /**
     * The attributes for CRUD methods
     *
     * @var array
     */
    protected $columns = ['title', 'image'];

    /**
     * The attributes for CRUD methods
     *
     * @var array
     */
    protected $searchable = ['title'];

    /**
     * Get creation form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCreateForm()
    {
        return view('admin.maps-create', $this->data());
    }

    // Todo: refactor it
    public function getCreatableAttribute() {
        return true;
    }

    public function locations()
    {
        return $this->hasMany(Location::class, 'map_id', 'id');
    }

    /**
     * Get preview for image
     *
     * @return string
     */
    public function getImage() {
        return $this->image ? '<img width="50" class="img" src="'. storage_url($this->image).'">' : '';
    }
}
