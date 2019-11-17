<?php

use JPeters\Architect\Events\ArchitectRunning;
use JPeters\Architect\Http\Middleware\ArchitectIsRunning;

return [
    'name' => 'Architect',

    // The route to access the admin panel
    'route' => 'architect',

    'upload_directory' => 'architect-uploads',

    'middleware' => [
        'web',
        'auth',
        ArchitectIsRunning::class,
    ]
];