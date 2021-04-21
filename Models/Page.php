<?php

namespace App\Models;

class Page extends Article
{
    const HOME = 1;
    /**
     * Alias for news
     *
     * @var string
     */
    const NEWS_ALIAS = 'news';
    /**
     * Alias for news
     *
     * @var string
     */
    const GALLERY_ALIAS = 'galleries';
    /**
     * Alias for galleries
     *
     * @var string
     */
    const VIDEOS_ALIAS = 'videos';
    /**
     * Alias for videos
     *
     * @var string
     */
    const BROADCAST_ALIAS = 'broadcast';
    /**
     * Alias for broadcast
     *
     * @var string
     */
    const CONTACTS_ALIAS = 'contacts';
    /**
     * Alias for broadcast
     *
     * @var string
     */
    const SEARCH_ALIAS = 'search';
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
    protected $routeSelector = 'pages';

    /**
     * The attributes for CRUD methods
     *
     * @var array
     */
    public $fields = [
        'title' => [
            'type' => 'string',
        ],
        'alias' => [
            'label' => 'Url',
            'type' => 'hidden',
            'show_view' => true,
            'show_mutator' => 'getUrl'
        ],
        'content' => [
            'label' => 'Text',
            'type' => 'text',
        ],
        'parent_id' => [
            'label' => 'Батьківська сторінка',
            'type' => 'select',
            'selector' => 'getPagesTree',
        ],
        'active' => [
            'type' => 'bool',
        ],
    ];

    protected $with = ['children'];

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
     * Select only pages
     *
     * @param bool $excludeDeleted
     * @return $this|\Illuminate\Database\Eloquent\Builder
     */
    public function newQuery($excludeDeleted = true) {
        return parent::newQuery($excludeDeleted)
            ->where('type', '=', 'page');

    }

    /**
     * Relation for get all children
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children() {
        return $this->hasMany(self::class, 'parent_id', 'id' );
    }

    /**
     * The recursive method for selection build
     *
     * @param array $pages
     * @param Page $page
     * @param string $prefix
     * @return array
     */
    private function getRecursiveSelect(Array $pages, $page, $prefix) {
        if(!empty($page->children)) {
            foreach ($page->children as $item) {
                $pages[] = [
                    'label' => $prefix . $item->title,
                    'value' => $item->id,
                ];
                $pages = $this->getRecursiveSelect($pages, $item, $prefix.' - ');
            }
        }
        return $pages;
    }

    /**
     * Selector for page selection in admin panel
     *
     * @return array
     */
    public function getPagesTree() {
        $home = self::find(static::HOME);
        $pages = [
            [
                'label' => $home->title,
                'value' => $home->id,
            ]
        ];
        return $this->getRecursiveSelect($pages, $home, ' - ');
    }

    /**
     * Get news page data
     */
    public static function newsPage() {
        return self::where('alias', static::NEWS_ALIAS)->first();
    }

    /**
     * Get gallery page data
     */
    public static function galleryPage() {
        return self::where('alias', static::GALLERY_ALIAS)->first();
    }

    /**
     * Get gallery page data
     */
    public static function videosPage() {
        return self::where('alias', static::VIDEOS_ALIAS)->first();
    }

    /**
     * Get gallery page data
     */
    public static function broadcastPage() {
        return self::where('alias', static::BROADCAST_ALIAS)->first();
    }

    /**
     * Get contacts page data
     */
    public static function contactsPage() {
        return self::where('alias', static::CONTACTS_ALIAS)->first();
    }

    /**
     * Get search page data
     */
    public static function searchPage() {
        return self::where('alias', static::SEARCH_ALIAS)->first();
    }
}
