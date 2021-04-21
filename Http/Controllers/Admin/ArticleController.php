<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ArticleRequest;
use App\Models\Article;
use App\Models\Menu;
use App\Models\Slider;
use App\Models\Tag;
use Laravelcrud\Crud\Http\Controllers\CrudController;
use Exception;
use DB;

class ArticleController extends CrudController
{
    protected $menu;
    /**
     * ArticleController constructor.
     * @param Article $model
     * @param Menu $menu
     */
    public function __construct(Article $model, Menu $menu)
    {
        parent::__construct();
        view()->share('title', 'Articles');
        $this->model = $model;
        $this->menu = $menu;
    }

    /**
     *
     * Display a listing of the resource.
     * GET /articles
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Laravelcrud\Crud\Http\Controllers\Response
     */
    public function index()
    {
        return $this->model->getList(function($query) {
            $query->whereIn('type', ['article', 'announcement']);
        });
    }

    /**
     * Store a newly created resource in storage.
     * POST /articles
     *
     * @param ArticleRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws Exception
     */
    public function store(ArticleRequest $request)
    {

        DB::beginTransaction();
        try {
            article_correction($request);
            $set_tags = $request->input('tags', []);
            $tags = [];
            foreach ($set_tags as $tag) {
                $get_tag = Tag::firstOrCreate(['title' => $tag]);
                $tags[] = $get_tag->id;
            }
            $request->request->remove('tags');
            $result = $this->model->createObject($request, compact('tags'));
            if($request->input('active') == 1 && $request->best == 1 && $this->model->getObject()) {
                Slider::create([
                    'item_id' => $this->model->getObject()->id,
                    'type' => 'article',
                ]);
            }
            DB::commit();
            $this->menu->forgetCache();
            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            $error = $this->model->errorMessage($e);
            return redirect()->back()
                ->with('error', $error);
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT /articles/{id}
     *
     * @param ArticleRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws Exception
     */
    public function update(ArticleRequest $request, $id)
    {

        DB::beginTransaction();
        try {
            article_correction_update($request);
            $set_tags = $request->input('tags', []);
            $tags = [];
            foreach ($set_tags as $tag) {
                $get_tag = Tag::firstOrCreate(['title' => $tag]);
                $tags[] = $get_tag->id;
            }
            $request->request->remove('tags');
            $result = $this->model->updateObject($id, $request, compact('tags'));
            if($request->input('active') == 1 && $request->best == 1) {
                Slider::firstOrCreate([
                    'item_id' => $id,
                    'type' => 'article',
                ]);
            }
            if($request->best == 0) {
                $slider = Slider::where('item_id', $id);
                $slider->delete();
            }

            DB::commit();
            $this->menu->forgetCache();
            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            $error = $this->model->errorMessage($e);
            return redirect()->back()
                ->with('error', $error);
        }
    }
}
