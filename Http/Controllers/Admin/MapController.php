<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\MapRequest;
use App\Models\Location;
use App\Models\Map;
use Laravelcrud\Crud\Http\Controllers\CrudController;
use Maatwebsite\Excel\Facades\Excel;
use GuzzleHttp\Client;
use DB;
use Validator;

class MapController extends CrudController
{
    /**
     * MapController constructor.
     * @param Map $model
     */
    public function __construct(Map $model)
    {
        parent::__construct();
        view()->share('title', 'Maps');
        $this->model = $model;
    }

    /**
     * Store a newly created resource in storage.
     * POST /pages
     *
     * @param MapRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function store(MapRequest $request)
    {
        DB::beginTransaction();
        try {
            $result = $this->model->createObject($request);
            $map = $this->model->getObject();
            $this->import($map, $request);
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', $this->model->errorMessage($e));
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT /pages/{id}
     *
     * @param MapRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function update(MapRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $result = $this->model->updateObject($id, $request);
            $map = $this->model;
            $this->import($map, $request);
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', $this->model->errorMessage($e));
        }
    }

    /**
     * The Excel file import
     *
     * @param $map
     * @param $request
     * @throws \Exception
     */
    private function import($map, $request)
    {
        $rules = [
            'nazva_zakhodu' => 'required|string|max:255',
            'zamovnik_zakhodu' => 'required|string|max:255',
            'termin_vikonannya' => 'required|string|max:255',
            'adresa_naseleniy_punkt' => 'required|string',
            'zagalna_koshtorisna_vartist_tis._grn' => 'numeric',
            'derzhavniy_byudzhet_tis._grn' => 'numeric',
            'derzhavniy_fond_onpstis._grn' => 'numeric',
            'oblasniy_fond_onps_tis._grn' => 'numeric',
            'mistseviy_byudzhet_tis._grn' => 'numeric',
        ];
        $customAttributes = [
            'nazva_zakhodu' => 'Назва заходу',
            'zamovnik_zakhodu' => 'Замовник заходу',
            'adresa_naseleniy_punkt' => 'Адреса, населений пункт',
            'termin_vikonannya' => 'Термін виконання',
            'zagalna_koshtorisna_vartist_tis._grn' => 'Загальна кошторисна вартість, тис. грн',
            'derzhavniy_byudzhet_tis._grn' => 'Державний бюджет, тис. грн',
            'derzhavniy_fond_onpstis._grn' => 'Державний фонд ОНПС,тис. грн',
            'oblasniy_fond_onps_tis._grn' => 'Обласний фонд ОНПС, тис. грн',
            'mistseviy_byudzhet_tis._grn' => 'Місцевий бюджет, тис. грн',
        ];
        $locales = config('translatable.locales');
        $data = $request->hasFile('path') ? Excel::load($request->file('path')->getRealPath())->formatDates(false)->get() : [];
        $locates = [];
        foreach ($data->toArray() as $i => $row) {
            foreach ($row as $field => &$item) {
                $item = is_numeric($item) && $field !== 'termin_vikonannya' ?  + $item : (string) $item;
                $row[trim($field)] = $item;
            }
            if(empty(array_filter($row))) {
                break;
            }
            $validator = Validator::make($row, $rules, [], $customAttributes);
            if($validator->fails()) {
                $errors = $validator->errors();
                $errors = $errors->all();
                throw new \Exception(implode(' ', $errors), 422);
            } else {
                $locate = [];
                foreach ($locales as $lang) {
                    $locate[$lang] = [
                        'title' => $row['nazva_zakhodu'],
                        'customer' => $row['zamovnik_zakhodu'],
                    ];
                }

                $locate['deadline'] = $row['termin_vikonannya'];
                $locate['total_cost'] = $row['zagalna_koshtorisna_vartist_tis._grn'];
                $locate['national_budget'] = $row['derzhavniy_byudzhet_tis._grn'];
                $locate['state_fund'] = $row['derzhavniy_fond_onpstis._grn'];
                $locate['regional_fund'] = $row['oblasniy_fond_onps_tis._grn'];
                $locate['local_budget'] = $row['mistseviy_byudzhet_tis._grn'];
                try {
                    list($locate['lat'], $locate['lng']) = $this->getLocation($row['adresa_naseleniy_punkt']);
                } catch (\Exception $e) { }
                $locates[] = new Location($locate);
            }
        }
        $map->locations()->saveMany($locates);
    }

    /**
     * The method for lat and lng getting
     *
     * @param $address
     * @return array
     */
    private function getLocation($address)
    {
        $address = urlencode($address);
        $url = "http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=Ukraine";
        $client = new Client();
        $response = $client->get($url);
        $response = json_decode($response->getBody());
        if(!empty($response->results)) {
            $lat = $response->results[0]->geometry->location->lat;
            $lng = $response->results[0]->geometry->location->lng;
            return [$lat, $lng];
        }
    }
}
