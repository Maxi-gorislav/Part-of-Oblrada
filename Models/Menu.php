<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravelcrud\Crud\Crud;

class Menu extends Model
{
    use Crud;

    /**
     * Home menu name
     */
    const HOME_MENU = 'Головне меню';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['menu'];

    /**
     * The attributes for CRUD methods
     *
     * @var array
     */
    public $fields = [
        'title' => [
            'type' => 'custom',
            'view' => 'admin.title-readonly',
            'data_mutator' => 'data'
        ],
        'pages' => [
            'type' => 'multiselect',
            'selector' => 'getArticleItems',
            'show_mutator' => 'showNamePages'
        ]
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

    /**
     * Get pages for menu building
     *
     * @return mixed
     */
    public function getArticleItems() {
        return Article::getActive()
            ->where('type', 'page')
            ->get()
            ->map(function ($object) {
                return [
                    'label' => $object->title,
                    'value' => $object->id,
                ];
            });
    }

    /**
     * The relation to Articles
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function items() {
        return $this->belongsToMany(Article::class);
    }

    /**
     * Mutator for pages attribute
     *
     * @return array
     */
    public function getPagesAttribute() {
        return array_column($this->items->toArray(), 'id');
    }

    /**
     * Mutator for permission to delete
     *
     * @return bool
     */
    public function getDeletableAttribute() {
        return $this->title !== self::HOME_MENU;
    }

    /**
     * Mutator for list pages showing
     *
     * @return string
     */
    public function showNamePages() {
        $str = implode('</li><li>', array_column($this->items->toArray(), 'title'));
        return "<ul><li>$str</li></ul>";
    }

    /**
     * Get home menu
     *
     * @return mixed
     */
    public static function getHomeMenu() {
        return self::where('title', self::HOME_MENU)
            ->with('items')
            ->first();
    }

    /**
     * Forget cache pages
     */
    public function forgetCache() {
        \Cache::forget('home-menu');
        \Cache::forget('home-news');
        \Cache::forget('home-slider');
    }
}
