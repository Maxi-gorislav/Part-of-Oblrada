<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\MediaRequest;
use App\Models\PanoramaMedia;
use Laravelcrud\Crud\Http\Controllers\CrudController;

class PanoramaMediaController extends CrudController
{
    /**
     * PanoramaController constructor.
     * @param PanoramaMedia $model
     */
    public function __construct(PanoramaMedia $model)
    {
        parent::__construct();
        view()->share('title', 'Panorama Media');
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
        $articles = $request->only(['panorama']);
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
        $articles = $request->only(['panorama']);

        $request->replace($request->except(['panorama']));
        return $this->model->updateObject($id, $request, compact('articles'));
    }

}
