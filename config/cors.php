<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        '*',
        'https://www.vet119.com',
        'http://www.vet119.com',
        'https://vet119.com',
        'http://vet119.com',
        'http://localhost:9000',
    ],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
