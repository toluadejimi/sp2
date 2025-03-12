<?php

return [
    'name' => 'Sprint Pay',
    'short_name' => 'MyPWA',
    'start_url' => '/',
    'background_color' => '#ffffff',
    'theme_color' => '#000000',
    'display' => 'standalone',
    'orientation' => 'portrait',
    'status_bar' => 'black',
    'icons' => [
        '72x72'   => '/assets/images/logo.png',
        '96x96'   => '/assets/images/logo.png',
        '128x128' => '/assets/images/logo.png',
        '144x144' => '/assets/images/logo.png',
        '152x152' => '/assets/images/logo.png',
        '192x192' => '/assets/images/logo.png',
        '384x384' => '/assets/images/logo.png',
        '512x512' => '/assets/images/logo.png',
    ],
    'service_worker' => [
        'enabled' => true,
        'path' => '/sw.js',
    ],
];
