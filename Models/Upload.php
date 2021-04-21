<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Laravelcrud\Crud\Crud;
use Illuminate\Support\Str;
use Route;
use Cache;

class Upload extends Model
{
    use Crud;

    /**
     * The translation model
     *
     * @var string
     */
    //  public $translationModel = ArticleTranslation::class;

    /**
     * Get the default foreign key name for the model.
     *
     * @return string
     */
    public function getForeignKey() {
        return Str::snake(str_singular($this->getTable())).'_'.$this->primaryKey;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'created_at', 'link'
    ];

    //  protected $with = ['parent', 'translations'];

    /**
     * @var array
     */
    public $translatedAttributes = [
        'name',  'link'
    ];

    /**
     * The attributes for CRUD methods
     *
     * @var array
     */

    public $fields = [
        'name' => [
            'type' => 'string',
        ],
        'created_at' => [
            'type' => 'timestamp',
        ],
        'link' => [
            'type' => 'string',
        ]
    ];

    /**
     * The attributes for CRUD methods
     *
     * @var array
     */
    protected $columns = ['name', 'created_at', 'link'];

    /**
     * The attributes for CRUD methods
     *
     * @var array
     */
    protected $searchable = ['name', 'link'];

    /**
     * The relation to category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
//    public function category() {
//        return $this->belongsTo(Category::class);
//    }

    /**
     * Get categories list
     *
     * @return mixed
     */
//    public function getCategories() {
//        return Category::get()->map(function ($object){
//            return [
//                'label' => $object->title,
//                'value' => $object->id,
//            ];
//        });
//    }

    /**
     * Get categories list
     *
     * @return mixed
     */
//    public function getTagsValues() {
//        return $this->tags->pluck('title')->toArray();
//    }

    /**
     * Get categories list
     *
     * @return mixed
     */
//    public function getTags() {
//        return Tag::get()->map(function ($object){
//            return [
//                'label' => $object->title,
//                'value' => $object->title,
//            ];
//        });
//    }
//
//    public function getCategoryView() {
//        if($category = $this->category) {
//            return view('front.components.category-badge', compact('category'));
//        }
//    }
//
//    public function getUrl() {
//        return route_if_exists('front.page.'.$this->attributes['alias']);
//    }

    /**
     * Get types list
     *
     * @return mixed
     */
//    public function getTypes() {
//        return [
//            [
//                'label' => 'New',
//                'value' => 'article',
//            ],
//            [
//                'label' => 'Announcement',
//                'value' => 'announcement',
//            ],
//        ];
//    }

    /**
     * The relation for parent page getting
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
//    public function parent() {
//        return $this->belongsTo(self::class, 'parent_id', 'id');
//    }
//
//    /**
//     * The relation for child pages getting
//     *
//     * @return \Illuminate\Database\Eloquent\Relations\HasMany
//     */
//    public function children() {
//        return $this->hasMany(self::class, 'parent_id', 'id')->where('active', 1);
//    }
//
//    /**
//     * The relation for parent page getting
//     *
//     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
//     */
//    public function tags() {
//        return $this->belongsToMany(Tag::class, 'article_tag');
//    }
//
//    /**
//     * Get preview for image
//     *
//     * @return string
//     */
//    public function getImage() {
//        return $this->image ? '<img width="200" class="img" src="'. storage_url($this->image).'">' : '';
//    }

    /**
     * The method for select only articles
     *
     * @return mixed
     */
//    public static function getArticles() {
//        return self::where('type', 'article');
//    }

    /**
     * Recursive method for breadcrumb building
     *
     * @param $page
     * @return array
     */
    private function getRecursiveBreadcrumb($page) {
        $item = [
            'alias' => $page->alias,
            'title' => $page->title,
        ];
        if($page->parent) {
            return array_merge($this->getRecursiveBreadcrumb($page->parent), [$item]);
        }
        return [$item];
    }

    /**
     * Get breadcrumb pages array
     *
     * @return array
     */
    public function getBreadcrumbPages() {
        return $this->getRecursiveBreadcrumb($this);
    }

    /**
     * The method for routes building of pages
     */
//    public static function routes() {
//        Route::group([
//            'as' => 'page.',
//        ], function () {
//            $pages = Cache::remember('pages', config('app.env') === 'production' ? (60 * 12) : 60, function() {
//                return self::select(['alias', 'parent_id'])->with('parent')->where('active', 1)->get();
//            });
//
//            foreach ($pages as $page) {
//                $array_aliases = self::getRecursiveAlias($page);
//                Route::get(implode('/', $array_aliases), ['uses' => 'ArticleController@index', 'as' => $page->alias]);
//            }
//        });
//    }

    /**
     * The recursive method for array aliases getting
     *
     * @param \Illuminate\Database\Eloquent\Model $page
     * @return array
     */
//    private static function getRecursiveAlias($page) {
//        if($page->parent) {
//            return array_merge(self::getRecursiveAlias($page->parent), [$page->alias]);
//        }
//        return [$page->alias];
//    }

    /**
     * Get active articles
     *
     * @return mixed
     */
    public static function getActive() {
        return self::where('active', 1);
    }

    /**
     * The mutator for top field
     *
     * @return bool
     */
//    public function getTopAttribute() {
//        return (bool) Slider::where('item_id', $this->getAttribute('id'))->where('type', 'article')->first();
//    }

    public function mediaItems() {
        return $this->belongsToMany(Media::class, 'article_media');
    }

    /**
     * Forget cache pages
     */
    public function forgetCache() {
        Cache::forget('pages');
    }
}
