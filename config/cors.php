<?php
return [
    'paths' => ['api/*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        '192.168.1.14:8000',
        'http://localhost:3000', // Include any other frontend URLs you may be using
        'http://127.0.0.1:8000',
        'http://localhost',
        'http://127.0.0.1',
        'http://localhost:5173',
        '192.168.1.14:5173',
        'http://192.168.1.6:5173',
        'http://192.168.1.6:8000',
    ],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];

