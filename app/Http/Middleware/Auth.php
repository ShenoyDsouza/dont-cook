<?php
namespace App\Http\Middleware;

use App\Models\Auth as AuthModel;
use App\Models\Common;
use Closure;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Config;

class Auth
{ //check for auth token
    public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->bearerToken();
        if (!$token) {
            $res = ["status" => false,
                "message" => "Invalid Token.",
                "data" => (object) (array("errors" => array("Invalid Token.")))];
            $request->user_id = 0;
            Common::insertLogs($request, $res);
            return response($res, Config::get('constants.STATUS_CODES.UNAUTHORIZED'));
        }
        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), [env('JWT_ALG')]);
        } catch (ExpiredException $e) {
            $res = ["status" => false,
                "message" => "Provided token is expired.",
                "data" => (object) (array("errors" => array("Provided token is expired.")))];
            $request->user_id = 0;
            Common::insertLogs($request, $res);
            return response($res, Config::get('constants.STATUS_CODES.UNAUTHORIZED'));
        } catch (Exception $e) {
            $res = ["status" => false,
                "message" => "An error while decoding token.",
                "data" => (object) (array("errors" => array("An error while decoding token.")))];
            $request->user_id = 0;
            Common::insertLogs($request, $res);
            return response($res, Config::get('constants.STATUS_CODES.UNAUTHORIZED'));
        }
        $user = AuthModel::getUser($credentials->id);
        // Now let's put the user in the request class so that you can grab it from there
        $request->auth = $user;
        $request->user_id = $request->auth->customer_id;
        return $next($request);
    }
}
