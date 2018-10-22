<?php
return [
    'URL' => [
        'APP_VERSION' => 'api/v1/',
    ],
    'TOKEN' => [
        'TOKEN_EXPIRY_TIME' => 86400,
    ],
    'STATUS_CODES' => [
        'SUCCESS' => 200,
        'BAD_REQUEST' => 400,
        'UNAUTHORIZED' => 401,
        'FORBIDDEN' => 403,
        'NOT_FOUND' => 404,
        'METHOD_NOT_ALLOWED' => 405,
        'CONFLICT' => 409,
        'INTERNAL_SERVER_ERROR' => 500,
    ],
    'PAGINATION' => [
        'OFFSET' => 0,
        'LIMIT' => 20,
        'LIMIT_MAX' => 1000,
        'ORDER_BY' => 'ASC',
    ],
];
