<?php
namespace App\Http\Controllers;

use App\User;
use App\Models\Common;
use App\Models\Auth;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use Laravel\Lumen\Routing\Controller as BaseController;

class AuthController extends BaseController
{
    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    private $request;
    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    /**
     * Create a new token.
     *
     * @param  \App\User   $user
     * @return string
     */
    private function _generateToken($user)
    {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'id' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + Config::get('constants.TOKEN.TOKEN_EXPIRY_TIME'), // Expiration time 4 hours
        ];

        // As you can see we are passing `JWT_SECRET` as the second parameter that will
        // be used to decode the token in the future.
        return JWT::encode($payload, env('JWT_SECRET'));
    }
    /**
     * Authenticate a user and return the token if the provided credentials are correct.
     *
     * @param  \App\User   $user
     * @return mixed
     */
    public function authenticate()
    {
        // echo password_hash('12345678',PASSWORD_DEFAULT);exit;
        $validator = Validator::make($this->request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $errors = $validator->errors();
        $errors = $errors->all();

        if ($errors) {
            $res = ["status" => false,
                    "message" => "Invalid Params.",
                    "data" => (object)(array("errors"=>$errors))];
            return response($res, Config::get('constants.STATUS_CODES.BAD_REQUEST'));
        }

        try {
            
           $user=Auth::getUserDetails($this->request->input('email'));
            // if user exist
            if (!$user) {
                $errors[]="Email does not exist.";
                $res = ["status" => false,
                        "message" => "Email does not exist.",
                        "data" => (object)(array("errors"=>$errors))];
                return response($res, Config::get('constants.STATUS_CODES.BAD_REQUEST'));
            }

            // Verify the password and generate the token
            if (password_verify($this->request->input('password'), $user->password)) {
                $token=$this->_generateToken($user);
                $res = ["status" => true,
                        "message" => "Data Found.",
                        "data" => (object)(array("user_data"=>$user,"token"=>$token))];
                return response($res, Config::get('constants.STATUS_CODES.SUCCESS'));
            }else{
                $errors[]="Email or password is wrong.";
                $res = ["status" => false,
                        "message" => "Email or password is wrong.",
                        "data" => (object)(array("errors"=>$errors))];
                return response($res, Config::get('constants.STATUS_CODES.BAD_REQUEST'));
            }

        } catch (\Illuminate\Database\QueryException $ex) {
            $errors[]=$ex->getMessage();
            $res = ["status" => false,
                    "message" => $ex->getMessage(),
                    "data" => (object)(array("errors"=>$errors))];
            return response($res, Config::get('constants.STATUS_CODES.INTERNAL_SERVER_ERROR'));
        }

    }
}