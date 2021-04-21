<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\GalleryRequest;
use App\Models\Gallery;
use App\Models\Media;
use Illuminate\Support\Facades\DB;
use Laravelcrud\Crud\Http\Controllers\CrudController;
use Exception;

class GalleryController extends CrudController
{
    /**
     * GalleryController constructor.
     * @param Gallery $model
     */
    public function __construct(Gallery $model)
    {
        parent::__construct();
        view()->share('title', 'Galleries');
        $this->model = $model;
    }


    /**
     * Store a newly created resource in storage.
     * POST /articles
     *
     * @param GalleryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(GalleryRequest $request)
    {
        article_correction($request);
        DB::beginTransaction();
        try {
            $result = $this->model->createObject($request);
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
     * PUT /articles/{id}
     *
     * @param GalleryRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(GalleryRequest $request, $id)
    {
        article_correction_update($request);
        return $this->model->updateObject($id, $request);
    }
}
