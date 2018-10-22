<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Auth\Authorizable;

class Auth extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'test',
    ];

    public static function getUserDetails($email)
    {
        $data = DB::table('users')
            ->where('email', '=', $email)
            ->select('*')->first();
        return $data;
    }

    public static function getUser($id)
    {
        $data = DB::table('oc_customer')
            ->where('customer_id', '=', $id)
            ->select('*')->first();
        return $data;
    }
    public static function registerUser($firstname, $lastname, $email, $password)
    {
        $id = DB::table('users')->insertGetId(
            ['first_name' => $firstname, 'last_name' => $lastname, 'email' => $email, 'password' => $password]
        );
        return $id;
    }
}
