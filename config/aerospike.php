<?php

return [

    'namespace' => env('AEROSPIKE_NAMESPACE', 'default'),
    'set' => env('AEROSPIKE_SET', 'app'),

    'servers' => [
        'hosts' => [
            [
                'addr' => env('AEROSPIKE_HOST', 'localhost'),
                'port' => (int)env('AEROSPIKE_PORT', 3000)
            ]
        ]
    ]
];
