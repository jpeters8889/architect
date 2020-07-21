<?php

use JPeters\Architect\Http\Middleware\ArchitectIsRunning;
use JPeters\Architect\Http\Middleware\Authenticate;
use JPeters\Architect\Http\Middleware\CanAccessArchitect;

return [
    'name' => 'Architect',

    // The route to access the admin panel
    'route' => 'architect',

    'upload_directory' => 'architect-uploads',

    'middleware' => [
        'web',
        Authenticate::class,
        CanAccessArchitect::class,
        ArchitectIsRunning::class,
    ],

    'can_change_password' => true,

    'gateway' => null,
];
