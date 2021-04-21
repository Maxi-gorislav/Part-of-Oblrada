<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Requests\AuthRequest;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Password;
use DB;

class ResetPasswordController extends ApiController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords {
        reset as webReset;
    }

    /**
     * Get the guard to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Reset the given user's password.
     *
     * @param  AuthRequest  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function reset(AuthRequest $request)
    {
        return $request->isAPI() ? $this->apiReset($request) : $this->webReset($request);
    }


    /**
     * API reset the given user's password.
     *
     * @param AuthRequest $request
     * @return mixed
     */
    public function apiReset(AuthRequest $request)
    {

        if(DB::table(config('auth.passwords.users.table'))
            ->where('email', $request->input('email'))
            ->where('created_at', '>', Carbon::now()->addDays(-1))
            ->count() == 0)
        {
            $message = trans('passwords.token');
            $errors = ['token' => $message];
            return $this->response(compact('message', 'errors'), 422);
        }
        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) use ($request) {
                $this->resetPassword($user, $password, $request);
            }
        );
        $body = [
            'message' => trans($response)
        ];
        $code = 200;
        if($response != Password::PASSWORD_RESET) {
            $body['errors'] = [
                'token' => trans($response),
            ];
            $code = 422;
        }
        return $this->response($body, $code);
    }


    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        if($user->created_at < Carbon::now()->addDays(-1)) {
            return Password::INVALID_TOKEN;
        } else {
            $user->password = Hash::make($password);

            $user->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));

            $this->guard()->login($user);
        }
    }
}
