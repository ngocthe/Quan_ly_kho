<?php

namespace App\Http\Middleware;

use App\Helpers\Response;
use Closure;


class Company
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
        $company_id = $request->header('x-company-id');
        
    }
}
