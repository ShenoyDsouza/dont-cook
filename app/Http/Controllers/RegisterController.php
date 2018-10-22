<?php
namespace App\Http\Controllers;

use App\Models\Auth;
use App\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller as BaseController;

class RegisterController extends BaseController
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
    public function user()
    {
        // echo password_hash('12345678',PASSWORD_DEFAULT);exit;
        $validator = Validator::make($this->request->all(), [
            'firstname' => 'required|string|max:50',
            'lastname' => 'required|string|max:50',
            'email' => 'required|email|max:70',
            'password' => 'required|max:70',
        ]);
        $errors = $validator->errors();
        $errors = $errors->all();

        if ($errors) {
            $res = ["status" => false,
                "message" => "Invalid Params.",
                "data" => (object) (array("errors" => $errors))];
            return response($res, Config::get('constants.STATUS_CODES.BAD_REQUEST'));
        }
        //check if email already exists
        $user = Auth::getUserDetails($this->request->input('email'));
        // if email already exists
        if ($user) {
            $res = ["status" => true,
                "message" => "Email already exist.",
                "data" => (object) (array("success" => false))];
            return response($res, Config::get('constants.STATUS_CODES.SUCCESS'));
        }

        //register user
        $firstname = $this->request->input('firstname');
        $lastname = $this->request->input('lastname');
        $email = $this->request->input('email');
        $password = password_hash($this->request->input('password'), PASSWORD_DEFAULT);
        $userRegister = Auth::registerUser($firstname, $lastname, $email, $password);
        $user = Auth::getUserDetails($this->request->input('email'));

        if ($userRegister) {
            // Verify the password and generate the token
            if (password_verify($this->request->input('password'), $user->password)) {
                $token = $this->_generateToken($user);
                $res = ["status" => true,
                    "message" => "User Registered.",
                    "data" => (object) (array("user_data" => $user, "token" => $token))];
                return response($res, Config::get('constants.STATUS_CODES.SUCCESS'));
            }
            $errors[] = "Email or password is wrong.";
            $res = ["status" => false,
                "message" => "Email or password is wrong.",
                "data" => (object) (array("errors" => $errors))];
            return response($res, Config::get('constants.STATUS_CODES.BAD_REQUEST'));
        }

    }
}
