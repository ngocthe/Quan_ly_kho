<?php

namespace App\Http\Middleware;

use App\Helpers\Response;
use Closure;


class Permission
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
        // $action = $request->route()->getAction();
        // $company_id = $request->header('x-company-id');
        // if(isset($company_id))
        //     {
        //         $request->request->add(['company_id' => $company_id]);
        //         auth()->user()->company_id= $company_id;
        //     return $next($request);
        // }
        // if (in_array('permission', $action['middleware'])) {
        //     $controller = $action['controller'];
        //     if (auth()->user()->hasPermission($controller))  return $next($request);
        // }
        return $next($request);
        // return Response::unauthorized();
    }
}
