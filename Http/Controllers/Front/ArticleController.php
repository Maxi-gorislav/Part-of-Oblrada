<?php

namespace App\Http\Controllers\Front;

use App\Models\Article;
use App\Models\Gallery;
use App\Models\Page;
use App\Models\Video;
use Route;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ArticleController extends BaseController
{
    /**
     * PageController constructor.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Build static page content
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        $route = Route::getCurrentRoute();
        if(empty($route->parameters['url_slug'])){
            $aliases = explode('/', $route->uri);
            $page = Article::getActive()->where('alias', end($aliases))->first();
        } else {
            $aliases = $route->parameters['url_slug'];
            $page = Article::getActive()
                ->where('alias', $aliases)
                ->first();
        }
        $page->viewed += 1;
        $page->save();

        if($page->type == 'photo-gallery') {
            $page = Gallery::find($page->id);
        }
        if($page->type == 'announcement' OR $page->type == 'page'){
            return view('front.pagecustom', compact('page'));
        } else{
            return view('front.page', compact('page'));
        }

    }

    public function gallery() {
        $route = Route::getCurrentRoute();

        if(empty($route->parameters['url_slug'])){
            $aliases = explode('/', $route->uri);

            $page = Article::getActive()->where('alias', end($aliases))->first();
        } else {

            $aliases = $route->parameters['url_slug'];

            $page = Gallery::where('alias', $aliases)->first();

        }
        $page->viewed += 1;
        $page->save();
        return view('front.page', compact('page'));
    }


    public function articles() {

        $route = Route::getCurrentRoute();
        $aliases =  $route->parameters['url_slug'];

        $page = Article::getActive()
            ->where('alias', $aliases)
            ->first();
        $page->viewed += 1;
        $page->save();
        if($page->type == 'photo-gallery') {

            $page = Gallery::find($page->id);
        }
        return view('front.page', compact('page'));
    }

    /**
     * The list of news
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function news() {

        $page = Page::newsPage();
        $selected_date = request('date') ?? 'all';

        $news = Article::getActive()
            ->with('category')
            ->where('type','article')
            ->where(function ($query) {
                $query->where('published_at', '<=', Carbon::now()->toDateTimeString())
                    ->orWhere('published_at', null);
            })
            ->orderBy('created_at', 'desc');

        // query for all news
        if($selected_date !== 'all') {
            $news = $news->whereDate('created_at', $selected_date);
        }

        // Get data from DB
        $news = $news->paginate(12);

        return view('front.news', compact('page', 'news'));
    }

    /**
     * The list of news
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function galleries() {

        $page = Page::galleryPage();
        $selected_date = request('date') ?? 'all';

        // query for galleries
        $galleries = Gallery::getActive()
            ->orderBy('created_at', 'desc');

        // query for all galleries
        if($selected_date !== 'all') {
            $galleries = $galleries->whereDate('created_at', $selected_date);
        }

        // Get data from DB
        $galleries =  $galleries->paginate(9);

        return view('front.galleries', compact('page', 'galleries'));
    }

    /**
     * The home page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function home() {

        //  $data = \Cache::remember('home-news', config('app.env') === 'production' ? (60 * 12) : 60, function() {
        // query for top news
        $top_news = Article::getActive()
            ->with('category')
            ->where('type', 'article')
            ->where('pop', '1')
            ->where(function ($query) {
                $query->where('published_at', '<=', Carbon::now()->toDateTimeString())
                    ->orWhere('published_at', null);
            })
            ->orderBy('viewed', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();


        $top_news_ids = array_column($top_news->toArray(), 'id');

        // query for others news
        $other_news = Article::getActive()
            ->with('category')
            ->where('type', 'article')
            ->where(function ($query) {
                $query->where('published_at', '<=', Carbon::now()->toDateTimeString())
                    ->orWhere('published_at', null);
            })
            ->orderBy('created_at', 'desc')
            // ->whereNotIn('id', $top_news_ids)
            ->limit(9)
            ->get();

        $other_news_ids = array_column($other_news->toArray(), 'id');

        $string_news = Article::getActive()
            ->where('type', 'article')
            ->where(function ($query) {
                $query->where('published_at', '<=', Carbon::now()->toDateTimeString())
                    ->orWhere('published_at', null);
            })
            ->whereNotIn('id', array_merge($top_news_ids, $other_news_ids))
            ->orderBy('created_at', 'desc')
            ->orderBy('viewed', 'desc')
            ->limit(3)
            ->get();

        // query for announcements
        $announcements = Article::getActive()
            ->with('category')
            ->where('type', 'announcement')
            ->where(function ($query) {
                $query->where('published_at', '<=', Carbon::now()->toDateTimeString())
                    ->orWhere('published_at', null);
            })
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        //     return compact('top_news', 'other_news', 'string_news', 'announcements');
        //    });

        return view('front.home', compact('top_news', 'other_news', 'string_news', 'announcements'));
    }

    /**
     * The list of videos
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function videos() {
        $page = Page::videosPage();
        $selected_date = request('date') ?? 'all';

        // query for galleries
        $videos = Video::orderBy('created_at', 'desc');

        // query for all galleries
        if($selected_date !== 'all') {
            $videos = $videos->whereDate('created_at', $selected_date);
        }

        // Get data from DB
        $videos = $videos->paginate(9);
        return view('front.videos', compact('page', 'videos'));
    }

    public function contacts() {
        $page = Page::contactsPage();
        return view('front.contacts', compact('page'));
    }

    public function search(Request $request)
    {

        $page = Page::searchPage();
        $q = $request->input('q');
        $articles = [];
        if($q) {
            $articles = Article::getActive()->whereHas('translations', function($query) use ($q) {
                $query->where(function ($query) use ($q) {
                    $query->where('title', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%")
                        ->orWhere('content', 'like', "%{$q}%");
                })->where('locale', \App::getLocale());
            })
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }
        return view('front.search', compact('page', 'articles'));
    }
}