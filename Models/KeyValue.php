<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravelcrud\Crud\Crud;

class KeyValue extends Model
{
    use Crud;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_key', 'item_value', 'locale'
    ];

    public $routeSelector = 'settings';

    /**
     * The attributes for CRUD methods
     *
     * @var array
     */
    public $fields = [
        'item_key' => [
            'type' => 'string',
            'label' => 'Name',
            'help' => 'This field isn\'t editable.'
        ],
        'item_value' => [
            'type' => 'string',
            'label' => 'Value'
        ],
        'locale' => [
            'type' => 'string',
        ],
    ];

    /**
     * The attributes for CRUD methods
     *
     * @var array
     */
    protected $columns = ['item_key', 'item_value', 'locale'];

    /**
     * The attributes for CRUD methods
     *
     * @var array
     */
    protected $searchable = ['item_key', 'item_value', 'locale'];

    /**
     * Forget cache pages
     */
    public function forgetCache() {
        \Cache::forget('settings');
    }
}
