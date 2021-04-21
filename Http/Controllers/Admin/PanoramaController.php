<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\PanoramaRequest;
use Illuminate\Http\Request;
use App\Models\Panorama;
use App\Models\PanoramaMedia;
use App\Models\Media;
use Illuminate\Support\Facades\DB;
use Laravelcrud\Crud\Http\Controllers\CrudController;
use Exception;

class PanoramaController extends CrudController
{
    /**
     * GalleryController constructor.
     * @param Panorama $model
     */
    public function __construct(Panorama $model)
    {
        parent::__construct();
        view()->share('title', 'Panorama');
        $this->model = $model;
    }


    /**
     * Store a newly created resource in storage.
     * POST /articles
     *
     * @param PanoramaRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PanoramaRequest $request)
    {
        $articles = $request->only(['article']);
        article_correction($request);
        DB::beginTransaction();
        try {
            $result = $this->model->createObject($request,compact('articles'));
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
     * @param PanoramaRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PanoramaRequest $request, $id)
    {
        $articles = $request->only(['article']);
        article_correction($request);
        return $this->model->updateObject($id, $request, compact('articles'));
    }
    /**

     */
    public function updateMedia(Request $request, $data)
    {

        $medias = $request->all();
        if(isset($medias['deletes'])||isset($medias['orders'])||$medias['height']!= null||$medias['width']!= null){
            if(isset($medias['orders'])){
                foreach ($medias['orders'] as $key =>$media){
                    PanoramaMedia::where(['id' => $key])->update(['position' => $media]);
                }
            }
            if($medias['height']!= null||$medias['width']!= null){
                if($medias['height']!= null){
                    Panorama::where(['id' => $medias['id']])->update(['height' => $medias['height']]);
                }
                if($medias['width']!= null){
                    Panorama::where(['id' => $medias['id']])->update(['width' => $medias['width']]);
                }

            }
            if(isset($medias['deletes'])){
                foreach ($medias['deletes'] as $key =>$media){
                    PanoramaMedia::where(['id' => $key])->delete();
                }
            }
            return json_encode("good");
        }
        else
            return json_encode( "bad");
    }
    public function panoramaSize(Request $request)
    {
//        dd('test');
        $medias = $request->all();
        return json_encode($medias);
    }
}
