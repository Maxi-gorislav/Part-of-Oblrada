<?php

namespace App\Models;

use App\Events\SliderCreated;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'slider';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_id', 'type',
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => SliderCreated::class,
    ];
}
