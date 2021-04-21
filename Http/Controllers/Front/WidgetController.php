<?php

namespace App\Http\Controllers\Front;

use App\Models\Article;
use App\Models\Media;
use DB;
use App\Models\Gallery;
use App\Models\Map;
use App\Models\Menu;
use App\Models\Slider;
use App\Models\Video;
use Route;

class WidgetController extends BaseController
{
    /**
     * GalleryController constructor.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Get list photo galleries
     *
     * @return mixed
     */
    public function getAllGalleries() {
        return Gallery::getActive()->orderBy('created_at', 'desc')->limit(10)->get();
    }

    public function youtubeVideos($limit) {
        return Video::limit($limit)->orderBy('updated_at', 'desc')->get();
    }

    public function getHomeMenu() {
        return \Cache::remember('home-menu', config('app.env') === 'production' ? (60 * 12) : 60, function() {
            return Menu::getHomeMenu()->items->map(function ($object) {
                $route_name = 'front.page.' . $object->alias;
                return [
                    'title' => $object->title,
                    'url' => route($route_name),
                    'alias' => $object->alias,
                    'is_active' => Route::getCurrentRoute()->getName() === $route_name,
                    'children' => $object->children->map(function ($object) {
                        $route_name = 'front.page.' . $object->alias;
                        return [
                            'title' => $object->title,
                            'url' => route($route_name),
                            'alias' => $object->alias,
                            'is_active' => Route::getCurrentRoute()->getName() === $route_name,
                            'children' => $object->children
                        ];
                    })
                ];
            });
        });
    }

    public function getCarouselItems() {

        //  return \Cache::remember('home-slider', config('app.env') === 'production' ? (60 * 12) : 60, function() {
        $items = [];
        Slider::all()->each(function ($row) use (&$items) {
            $item = $row->type == 'article' ? Article::find($row->item_id) : Video::find($row->item_id);
            if ($item) {

                $item->type = $row->type;
                if ($item instanceof Video || ($item instanceof Article && $item->active == 1)) {
                    $items[] = $item;

                }
            } else {
                $row->delete();
            }
        });
//        usort($items, function($a, $b) {
//            return ($a['created_at'] < $b['created_at']) ? -1 : 1;
//        });
        $items = array_reverse($items);


        return $items;

        //  });

    }

    public function getMaps() {
        return Map::with('locations')->get();
    }
}
