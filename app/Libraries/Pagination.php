<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Config;

class Pagination
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->request = $request;
    }

    public static function isNumeric($offset, $limit, $sort, $order)
    {
        $data = [];
        $data['offset'] = $offset ? $offset : Config::get('constants.PAGINATION.OFFSET');
        $data['limit'] = $limit ? $limit : Config::get('constants.PAGINATION.LIMIT');
        $data['status'] = true;
        $data['sort'] = '';
        $data['order'] = Config::get('constants.PAGINATION.ORDER_BY');
        // $data['limit'] = min($data['limit'], Config::get('constants.PAGINATION.PAGE_LIMIT_MAX'));
        if (!(is_numeric($data['limit'])) || !(is_numeric($data['offset']))) {
            $response = ["status" => false,
                "data" => [
                    "errors" =>
                    ["offset/limit must be numeric"],
                ],
                "message" => "Invalid param"];
            return $response;
        }
        if ((isset($sort)) || (isset($order))) {
            if (!$sort || !$order) {
                $response = ["status" => false,
                    "data" => [
                        "errors" =>
                        ["sort/order value is required"],
                    ],
                    "message" => "Invalid param"];
                return $response;
            }
            $data['sort'] = $sort;
            $data['order'] = $order;

        }
        if ((isset($order) && (strtolower($order) != 'asc' && strtolower($order) != 'desc'))) {
            $response = ["status" => false,
                "data" => [
                    "errors" =>
                    ["order must asc or desc"],
                ],
                "message" => "Invalid param"];
            return $response;
        }
        return $data;
    }
    public static function isSortKeysValid($sortArr, $sortKey)
    {
        $tableName = '';
        foreach ($sortArr as $v) {
            //check if sort key passed in present in the pre defined array i.e. $sortArr
            if (in_array($sortKey, $v)) {
                $tableName = array_search($v, $sortArr);
                //if present them append the name of the array that is the table name
                if ($tableName != "") {
                    if ($tableName == "alias") {
                        return $data = ['status' => true,
                            'sortKey' => $sortKey];
                    } else {
                        return $data = ['status' => true,
                            'sortKey' => $tableName . '.' . $sortKey];
                    }
                }
            }
        }
        $response = ["status" => false,
            "data" => [
                "errors" =>
                ["sort key is invalid"],
            ],
            "message" => "Invalid param"];
        return $response;

    }
}
