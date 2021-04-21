<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\MediaRequest;
use App\Models\Media;
use Laravelcrud\Crud\Http\Controllers\CrudController;

class MediaController extends CrudController
{
    /**
     * MediaController constructor.
     * @param Media $model
     */
    public function __construct(Media $model)
    {
        parent::__construct();
        view()->share('title', 'Images');
        $this->model = $model;
    }

    /**
     * The list of images
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Laravelcrud\Crud\Http\Controllers\Response
     */
    public function index() {
        return $this->model->getList(function ($query) {
            $query->where('type', 'image');
        });
    }

    /**
     * Store a newly created resource in storage.
     * POST /media
     *
     * @param MediaRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(MediaRequest $request)
    {
        $articles = $request->only(['gallery','article' ]);
        return $this->model->createObject($request, compact('articles'));
    }

    /**
     * Update the specified resource in storage.
     * PUT /media/{id}
     *
     * @param MediaRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(MediaRequest $request, $id)
    {
        $articles = $request->only(['gallery','article' ]);

        $request->replace($request->except(['gallery','article' ]));
        return $this->model->updateObject($id, $request, compact('articles'));
    }
}
