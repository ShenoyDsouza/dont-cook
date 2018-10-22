<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Common extends Model
{
    public static function createResponse($values)
    {

    }
    public static function insertLogs($request, $response)
    {
        DB::table('api_logs')->insert([
            'method' => ($request->method()),
            'url' => $request->path(),
            'request' => json_encode($request->input()),
            'response' => isset($response->original)?json_encode($response->original):json_encode($response),
            'user_id' => $request->user_id,
            'ip_address' => $request->ip(),
            'status_code'=>isset($response->original)?$response->getStatusCode():json_encode($response),
            'created_at' => date('Y-m-d, H:i:s')]
        );
    }
}
