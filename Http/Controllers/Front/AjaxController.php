<?php

namespace App\Http\Controllers\Front;

use App\Http\Requests\Front\AjaxRequest;
use App\Mail\Contact;
use App\Models\Subscriber;
use Exception;
use Mail;

class AjaxController extends BaseController
{
    /**
     * The method for subscriber storing
     *
     * @param AjaxRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function subscribe(AjaxRequest $request) {
        try {
            Subscriber::create($request->only(['email']));
            return response()->json([
                'message'=> 'Ви успішно підписалися на щоденні оновлення',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'errors' => [
                    'email' => [
                        $e->getMessage()
                    ]
                ],
            ], 500);
        }
    }

    /**
     * The method for contact sending
     *
     * @param AjaxRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function contact(AjaxRequest $request) {
        try {
            Mail::send(new Contact($request->only(['full_name', 'email', 'message'])));
            return response()->json([
                'message'=> 'Ваше звернення успішно відправлено',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'errors' => [
                    'email' => [
                        $e->getMessage()
                    ]
                ],
            ], 500);
        }
    }
}
