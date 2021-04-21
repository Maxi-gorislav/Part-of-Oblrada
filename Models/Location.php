<?php

namespace App\Models;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Laravelcrud\Crud\Crud;

class Location extends Model
{
    use Crud, Translatable;

    /**
     * The translation model
     *
     * @var string
     */
    public $translationModel = LocationTranslation::class;

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['translations'];

    /**
     * @var array
     */
    public $translatedAttributes = [
        'title', 'customer',
    ];

    protected $fillable = [
        'title',
        'customer',
        'deadline',
        'total_cost',
        'national_budget',
        'state_fund',
        'regional_fund',
        'local_budget',
        'lat',
        'lng',
        'map_id',
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
        'customer' => [
            'type' => 'string',
        ],
        'deadline' => [
            'type' => 'string',
        ],
        'total_cost' => [
            'type' => 'number',
        ],
        'national_budget' => [
            'type' => 'number',
        ],
        'state_fund' => [
            'type' => 'number',
        ],
        'regional_fund' => [
            'type' => 'number',
        ],
        'local_budget' => [
            'type' => 'number',
        ],
        'address' => [
            'type' => 'string',
        ],
        'lat' => [
            'type' => 'hidden',
        ],
        'lng' => [
            'type' => 'hidden',
        ],
        'map_id' => [
            'selector' => 'getMaps',
            'type' => 'select',
        ],
    ];

    /**
     * The attributes for CRUD methods
     *
     * @var array
     */
    protected $columns = ['title', 'customer', 'deadline', 'lat', 'lng'];

    /**
     * The attributes for CRUD methods
     *
     * @var array
     */
    protected $searchable = ['title', 'customer'];

    public function map()
    {
        return $this->belongsTo(Map::class);
    }

    // Todo: refactor it
    public function getEditableAttribute() {
        return true;
    }

    public function getMaps() {
        return Map::all()->each(function ($item) {
           return [
               'label' => $item->title,
               'value' => $item->id,
           ];
        });
    }
}
