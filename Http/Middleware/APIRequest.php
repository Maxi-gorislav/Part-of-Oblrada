<?php

namespace App\Http\Middleware;

use Closure;

class APIRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if ($request->header('Accept') != 'application/json' && $request->route()->getName()!='password.reset') {
            return response()->json(['status'=>'Failed', 'message'=>trans('api.accept_header')], 422);
        }

        return $next($request);
    }
}
