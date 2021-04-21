<?php

namespace App\Models;


use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Laravelcrud\Crud\Crud;
use Illuminate\Support\Str;
use function PHPSTORM_META\type;
use Illuminate\Support\Facades\Storage;

class Panorama extends Article
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
    protected $routeSelector = 'panorama';

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
            'show_relation' => 'panoramaItems',
            'panorama' => true
        ],
        'article' => [
            'type' => 'select',
            'selector' => 'getArticlesPanorama',
            'show_mutator' => 'getArticleTitle',
            'column_mutator' => 'getArticleTitle',
        ]
    ];

    /**
     * The attributes for CRUD methods
     *
     * @var array
     */
    protected $columns = ['title', 'active','article'];

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
        return parent::newQuery($excludeDeleted)->where('type', 'panorama');
    }

    /**
     * Get preview for image
     *
     * @return string
     */
    public function getImage() {
        return '<img width="200" class="img" src="'. storage_url($this->image).'">';
    }
    /**
     * The relation to articles
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function articles() {
        return $this->belongsToMany(Article::class, 'article_has_panoramas','panorama_id','article_id');
//        return $this->hasMany(self::class, 'parent_id');
    }
    /**
     * Get articles list
     *
     * @return mixed
     */
    public function getArticlesPanorama() {
        return Article::getArticles()->get()->map(function ($object){
            return [
                'label' => $object->title,
                'value' => $object->id,
            ];
        })
            ->push([
            'label' => '---',
            'value' => '',
        ]);
    }

    public function getArticleTitle()
    {
        $article = $this->articles()->where('type', 'article')->first();
        return $article ? $article->title : '';
    }

    /**
     * Get preview for panorama
     *
     * @return string
     */
    public function getPanoramaEdit($images, $width = false, $height= false) {
        $panorama = array();
//        dd($width, $height);
        if($width == null){$width = 900;}
        if($height == null){$height = 675;}
        foreach ($images as $image)
        {
            array_push($panorama,array("path"=>storage_url($image->path),"id"=>$image->id));
//            dd($image->getOriginal('pivot_article_id'));
        }
        $panorama = json_encode($panorama);

        return ("          
                     <script src=\" " . asset('js/dist/lor-panorama-360.js') . "\"  type=\"text/javascript\"></script>
                     <script src=\"" . asset('js/jpreview.js') . "\" type=\"text/javascript\"></script>
                     <script src=\"" . asset('js/jquery.gridly.js') . "\" type=\"text/javascript\"></script>
                     <script src=\"" . asset('js/dist/lor-panorama-360-edit.js') . "\" type=\"text/javascript\"></script>
                     <script>
                     console.log('panorama '," . $panorama . ");
                     $('#3d_round_lun_setting').lor3DPanoramaEdit({
	                 targetPanorama: '#3d_round_lun',
	                 panoramaSetting: {
	                 source: [                      
//                                 \"http://rada.loc/storage/panorama/98e42b0a2831a4c4a17e2b5509367a1f.png\",
//                                 \"http://rada.loc/storage/panorama/0a0b742b9c74f9b0531175b63dab6319.png\",
//                                 \"http://rada.loc/storage/panorama/6091ac3483f62cfdda227d473fbb0973.png\",
                                  " . $panorama . "
                             ],
                             width: ". $width .",
                             height: ". $height .",
                             }
                         });
                     </script>
");
    }

    public function getPanorama($images, $width = false, $height= false) {
        $panorama = array();
//        dd($width, $height);
        if($width == null){$width = 900;}
        if($height == null){$height = 675;}
        foreach ($images as $image)
        {
//            $panorama .= '"'. storage_url($image->path) . '"' . ', ';
            array_push($panorama,storage_url($image->path));

//            array_push($panorama,array("path"=>Storage::url($image->path),"id"=>$image->id));
//            dd($image->getOriginal('pivot_article_id'));
        }
//        $panorama = implode(",", $panorama);
        $panorama = json_encode($panorama);

        return ("          
                     <script src=\" " . asset('js/dist/lor-panorama-360.js') . "\"  type=\"text/javascript\"></script>

                     <script>
                     console.log('panorama '," . $panorama . ");
                     $('#3d_round_panorama').lor3DPanorama({
                     	 source: 
                         	 " . $panorama . "
                         ,
                         width: ". $width .",
                         height: ". $height .",
	                 });
                     </script>
");
    }




}
