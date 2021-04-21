<?php

namespace App\Models;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Laravelcrud\Crud\Crud;
use Illuminate\Support\Str;
use function PHPSTORM_META\type;

class PanoramaMedia extends Model
{
    use Crud, Translatable;

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'panorama';

    /**
     * Routes group name
     *
     * @var string
     */
    protected $routeSelector = 'panorama_media';


    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['translations'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'path', 'url', 'type','position'
    ];

    /**
     * The translation model
     *
     * @var string
     */
    public $translationModel = PanoramaTranslation::class;

    /**
     * Get the default foreign key name for the model.
     *
     * @return string
     */
    public function getForeignKey() {
        return Str::snake($this->getTable()).'_'.$this->primaryKey;
    }

    /**
     * @var array
     */
    public $translatedAttributes = [
        'title', 'description'
    ];

    public $showTranslateTabs = false;

    /**
     * The attributes for CRUD methods
     *
     * @var array
     */
    public $fields = [
        'path' => [
            'label' => 'Image',
            'type' => 'file',
            'aspect_ratio' => 1,
            'show_mutator' => 'getImage',
            'column_mutator' => 'getSmallImage'
        ],
//        'gallery' => [
//            'type' => 'select',
//            'selector' => 'getGalleries',
//            'show_mutator' => 'getGalleryTitle',
//            'column_mutator' => 'getGalleryTitle',
//        ],
        'panorama' => [
            'type' => 'select',
            'selector' => 'getPanorama',
            'show_mutator' => 'getPanoramaTitle',
            'column_mutator' => 'getPanoramaTitle',
        ]
    ];

    /**
     * The attributes for CRUD methods
     *
     * @var array
     */
    protected $columns = ['path', 'panorama'];

    /**
     * The attributes for CRUD methods
     *
     * @var array
     */
    protected $searchable = [];

    /**
     * The relation to articles
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function articles() {
        return $this->belongsToMany(Article::class, 'article_panorama');
    }

    /**
     * Get galleries list
     *
     * @return mixed
     */
//    public function getGalleries() {
//        return Gallery::get()->map(function ($object){
//            return [
//                'label' => $object->title,
//                'value' => $object->id,
//            ];
//        })->push([
//            'label' => '---',
//            'value' => '',
//        ]);
//    }

    /**
     * Get articles list
     *
     * @return mixed
     */
    public function getPanorama() {
        return Panorama::get()->map(function ($object){
            return [
                'label' => $object->title,
                'value' => $object->id,
            ];
        });
//        ->push([
//            'label' => '---',
//            'value' => '',
//        ]);
    }
    
    /**
     * Get preview for image
     *
     * @return string
     */

    public function getImage()
    {
        return $this->path && $this->path != 'NULL' ? '<img width="200" class="img" src="'. storage_url($this->path).'">' : '';
    }

    /**
     * Get preview for image thumb
     *
     * @return string
     */

    public function getSmallImage()
    {
        return $this->path && $this->path != 'NULL' ? '<img width="50" class="img" src="'. storage_url(thumb($this->path)).'">' : '';
    }

    public function getArticleAttribute()
    {
        $article = $this->articles()->where('type', 'panorama')->first();
        return $article ? $article->id : '';
    }

    public function getPanoramaTitle()
    {
        $article = $this->articles()->where('type', 'panorama')->first();
        return $article ? $article->title : '';
    }
}
