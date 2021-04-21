<?php

namespace App\Models;

class Gallery extends Article
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'articles';

    /**
     * Routes group name
     *
     * @var string
     */
    protected $routeSelector = 'galleries';

    /**
     * The attributes for CRUD methods
     *
     * @var array
     */
    public $fields = [
        'title' => [
            'type' => 'string',
        ],
        'content' => [
            'label' => 'Text',
            'type' => 'text',
        ],
        'image' => [
            'label' => 'Image',
            'type' => 'file',
            'aspect_ratio' => 1.26,
            'show_mutator' => 'getImage'
        ],
        'active' => [
            'type' => 'bool',
        ],
        'items' => [
            'type' => 'hidden',
            'show_view' => true,
            'show_relation' => 'mediaItems'
        ]
    ];

    /**
     * The attributes for CRUD methods
     *
     * @var array
     */
    protected $columns = ['title', 'active'];

    /**
     * The attributes for CRUD methods
     *
     * @var array
     */
    protected $searchable = ['title', 'content'];

    /**
     * Set relation for together creating or updating
     *
     * @var string
     */
//    protected $set_relation = 'mediaItems';

    /**
     * Select only pages
     *
     * @param bool $excludeDeleted
     * @return $this|\Illuminate\Database\Eloquent\Builder
     */
    public function newQuery($excludeDeleted = true) {
        return parent::newQuery($excludeDeleted)->where('type', 'photo-gallery');
    }

    /**
     * Get preview for image
     *
     * @return string
     */
    public function getImage() {
        return '<img width="200" class="img" src="'. storage_url($this->image).'">';
    }
}
