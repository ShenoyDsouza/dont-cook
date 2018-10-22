<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Config;

class JsonContentTypeCheck
{
    /**
     * check for - content should be in the json format only
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!$request->isJson()) {
            $res = ["status" => false,
                "message" => "Content type must be json format",
                "data" => (object) (array("errors" => array("Content type must be json format")))];
            return response($res, Config::get('constants.STATUS_CODES.BAD_REQUEST'));
        }
        return $next($request);
    }
}
