<?php

namespace App\Http\Middleware;

use Closure;
use Route;
use Spatie\Permission\Models\Permission;

class Permissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $route_name_pieces = explode('.', Route::getCurrentRoute()->getName());
        $name_to_permisions = [
            'index' => 'read ',
            'show' => 'read ',
            'store' => 'create ',
            'create' => 'create ',
            'update' => 'edit ',
            'edit' => 'edit ',
            'destroy' => 'delete ',
            'updateMedia' => 'updateMedia ',
            'panoramaSize' => 'panoramaSize ',
        ];
        $hasPremission = Permission::where([
            'name' => $name_to_permisions[$route_name_pieces[2]].$route_name_pieces[1],
            'guard_name' => config('crud.guard')
        ])->first();
        if(auth(config('crud.guard'))->user()
            ->can($name_to_permisions[$route_name_pieces[2]].$route_name_pieces[1]) || !$hasPremission) {
            return $next($request);
        } else {
            return abort(404);
        }
    }
}
