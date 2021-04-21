<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;

abstract class ApiController extends Controller
{
    /**
     * The api response
     *
     * @param array $body
     * @param integer $code
     * @return array
     */
    protected function response($body = [], $code = 200)
    {

        $body['status'] = $code == 200 ? 'Ok' : 'Failed';
        return response()->json($body, $code);
    }
}
