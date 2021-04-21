<?php

namespace App\Models;

class Video extends Media
{
    /**
     * Routes group name
     *
     * @var string
     */
    protected $routeSelector = 'videos';

    public $showTranslateTabs = true;

    /**
     * The attributes for CRUD methods
     *
     * @var array
     */
    public $fields = [
        'title' => [
            'type' => 'string',
        ],
        'url' => [
            'type' => 'url',
            'show_mutator' => 'getSmallVideo',
            'column_mutator' => 'getSmallVideo',
            'help' => 'For example: https://www.youtube.com/watch?v=f9l950Q2N1w'
        ],
        'best' => [
            'label' => 'In top',
            'type' => 'bool',
        ],
    ];

    protected $appends = [
        'youtube'
    ];

    /**
     * The attributes for CRUD methods
     *
     * @var array
     */
    protected $columns = ['title', 'url'];

    /**
     * Select only pages
     *
     * @param bool $excludeDeleted
     * @return $this|\Illuminate\Database\Eloquent\Builder
     */
    public function newQuery($excludeDeleted = true) {
        return parent::newQuery($excludeDeleted)->where('type', 'video');
    }

    public function getSmallVideo() {
        return '<iframe width="200" height="112" src="' . $this->getAttribute('youtube') . '" frameborder="0" 
                    allow="autoplay; encrypted-media" allowfullscreen></iframe>';
    }

    public function getYoutubeAttribute() {
        preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $this->attributes['url'], $matches);
        if(!isset($matches[1])) {
            return false;
        }
        $video_id = $matches[1];
        return "https://www.youtube.com/embed/$video_id?showinfo=0&rel=0"; // &controls=0
    }

    /**
     * The mutator for top field
     *
     * @return bool
     */
    public function getTopAttribute() {
        return (bool) Slider::where('item_id', $this->getAttribute('id'))->where('type', 'video')->first();
    }
}
