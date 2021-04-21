<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\BroadcastRequest;
use App\Http\Requests\Admin\VideoRequest;
use App\Models\Article;
use App\Models\Video;
use App\Models\Slider;
use Laravelcrud\Crud\Http\Controllers\CrudController;
use Exception;
use DB;

class VideoController extends CrudController
{
    /**
     * The Broadcast model
     * @var Article|__anonymous@634
     */
    private $broadcast;
    /**
     * VideoController constructor.
     * @param Video $model
     */
    public function __construct(Video $model)
    {
        parent::__construct();
        view()->share('title', 'Videos');
        $this->model = $model;
        $this->broadcast = new class extends Article {
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
            protected $routeSelector = 'broadcast';

            /**
             * The attributes for CRUD methods
             *
             * @var array
             */
            public $fields = [
                'title' => [
                    'type' => 'string',
                ],
                'image' => [
                    'label' => 'Resource url',
                    'type' => 'string',
                ],
                'category_id' => [
                    'label' => 'Categories',
                    'type' => 'select',
                    'selector' => 'getCategories',
                ],
                'active' => [
                    'type' => 'bool',
                ],
            ];
        };
    }

    /**
     * The list of videos
     *
     * @return \Laravelcrud\Crud\Http\Controllers\Response
     * @throws \Throwable
     */
    public function index() {
        return parent::index()->with('top', view('admin.video-broadcast')->render());
    }

    /**
     * Store a newly created resource in storage.
     * POST /media
     *
     * @param VideoRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(VideoRequest $request)
    {
        DB::beginTransaction();
        try {
            $result = $this->model->createObject($request);
            if($request->input('top', false)) {
                Slider::create([
                    'item_id' => $this->model->getObject()->id,
                    'type' => 'video',
                ]);
            }
            DB::commit();
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
     * PUT /media/{id}
     *
     * @param VideoRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(VideoRequest $request, $id)
    {

        DB::beginTransaction();
        try {
            $result = $this->model->updateObject($id, $request);
            if($request->best == 1) {
                Slider::firstOrCreate([
                    'item_id' => $id,
                    'type' => 'video',
                ]);
            }
            if($request->best == 0) {
                $slider = Slider::where('item_id', $id);
                $slider->delete();
            }
            DB::commit();
            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            $error = $this->model->errorMessage($e);
            return redirect()->back()
                ->with('error', $error);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * GET /videos/broadcast
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function broadcastForm() {
        $broadcast_page = Article::where('alias', 'broadcast')->first();
        return $this->broadcast->getEditForm($broadcast_page->id);
    }

    /**
     * Update the specified resource in storage.
     * PUT /videos/broadcast/{id}
     *
     * @param BroadcastRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function broadcastUpdate(BroadcastRequest $request, $id) {
        if(!($active = $request->has('active'))) {
            $request->request->add(compact('active'));
        }
        return $this->broadcast->updateObject($id, $request);
    }
}
