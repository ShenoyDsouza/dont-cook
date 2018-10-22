<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use App\Models\Common;

class LogResponse
{
    /**
     * log request and response object of each request.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // return ($request);

        $response = $next($request);
        Common::insertLogs($request,$response);
        return $response;
    }
    
}
