<?php

use JPeters\Architect\Http\Middleware\ArchitectIsRunning;
use JPeters\Architect\Http\Middleware\Authenticate;

return [
    'name' => 'Architect',

    // The route to access the admin panel
    'route' => 'architect',

    'upload_directory' => 'architect-uploads',

    'middleware' => [
        'web',
        Authenticate::class,
        ArchitectIsRunning::class,
    ],
];
