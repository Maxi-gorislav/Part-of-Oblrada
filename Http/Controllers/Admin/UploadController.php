<?php

namespace App\Http\Controllers\Admin;

//use App\Http\Requests\Admin\ArticleRequest;
use Illuminate\Http\Request;
use App\Models\Upload;
use App\Models\Menu;
//use App\Models\Slider;
//use App\Models\Tag;
use Laravelcrud\Crud\Http\Controllers\CrudController;
use Exception;
use DB;


class UploadController extends CrudController
{
    protected $menu;
    /**
     * UploadController constructor.
     * @param Upload $model
     * @param Menu $menu
     */
    public function __construct(Upload $model, Menu $menu)
    {
        parent::__construct();
        view()->share('title', 'Upload');
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
        $files =  Upload::paginate(10);
        return view('admin.upload', compact('files'));
    }

    /**
     * Store a newly created resource in storage.
     * POST /articles
     *
     * @param ArticleRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws Exception
     */
    public function store(Request  $request)
    {
        $fifi = $request->file('file_link');

        if (!is_null($fifi)) {
            $filepath = $fifi->store('public/content-news');
            $filename_orig = $fifi->getClientOriginalName();
            preg_match('/(?<=\/).*/', $filepath, $filename_generated);
            $filename_generated = $filename_generated[0];
            Upload::insert([
                'name' => $filename_orig,
                'link' => $filename_generated,
                'created_at' => now(),
            ]);
        }
        return back();

//        DB::beginTransaction();
//        try {
//            article_correction($request);
//            $set_tags = $request->input('tags', []);
//            $tags = [];
//            foreach ($set_tags as $tag) {
//                $get_tag = Tag::firstOrCreate(['title' => $tag]);
//                $tags[] = $get_tag->id;
//            }
//            $request->request->remove('tags');
//            $result = $this->model->createObject($request, compact('tags'));
//            if($request->input('active') == 1 && $request->input('top', false) && $this->model->getObject()) {
//                Slider::create([
//                    'item_id' => $this->model->getObject()->id,
//                    'type' => 'article',
//                ]);
//            }
//            DB::commit();
//            $this->menu->forgetCache();
//            return $result;
//        } catch (Exception $e) {
//            DB::rollBack();
//            $error = $this->model->errorMessage($e);
//            return redirect()->back()
//                ->with('error', $error);
//        }
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
            article_correction($request);
            $set_tags = $request->input('tags', []);
            $tags = [];
            foreach ($set_tags as $tag) {
                $get_tag = Tag::firstOrCreate(['title' => $tag]);
                $tags[] = $get_tag->id;
            }
            $request->request->remove('tags');
            $result = $this->model->updateObject($id, $request, compact('tags'));
            if($request->input('active') == 1 && $request->input('top', false)) {
                Slider::firstOrCreate([
                    'item_id' => $id,
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
}
