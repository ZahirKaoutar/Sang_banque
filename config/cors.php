<?php
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://localhost:5173'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],  // ← Vérifiez que c'est bien ['*'] et non []
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
