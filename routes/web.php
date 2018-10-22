<?php
use Illuminate\Support\Facades\Config;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
 */

$router->get('/', function () use ($router) {
    $res = ["status" => false,
        "message" => "ACCESS FORBIDDEN.",
        "data" => (object) (array("errors" => array("ACCESS FORBIDDEN.")))];
    return response($res, Config::get('constants.BAD_REQUEST'));
});
//user login route
$router->post(
    Config::get('constants.URL.APP_VERSION') . 'auth/login',
    [
        'uses' => 'AuthController@authenticate',
    ]
);
$router->post(
    Config::get('constants.URL.APP_VERSION') . 'register/user',
    [
        'uses' => 'RegisterController@user',
    ]
);
//router group for api/v1 routes with middleware
$router->group(['prefix' => Config::get('constants.URL.APP_VERSION'), 'middleware' => 'jsonContentTypeCheck|auth|logResponse'], function () use ($router) {
    //routes for category
    $router->group(['prefix' => 'user'], function () use ($router) {
        $router->post('/user', ['uses' => 'RegisterController@user']); //get all category
    });

});



