<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;

class FormDataTypeCheck
{
    /**
     * check for - content should be in the multipart form data format only
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (explode(";", $request->headers->get('content-type'))[0] != 'multipart/form-data') {
            $res = ["status" => false,
                "message" => "Content type must be multipart/form-data format",
                "data" => (object) (array("errors" => array("Content type must be multipart/form-data format")))];
            return response($res, Config::get('constants.STATUS_CODES.BAD_REQUEST'));
        }
        return $next($request);
    }
}
