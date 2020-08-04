<?php

declare(strict_types=1);

namespace JPeters\Architect\Http\Controllers;

class HealthController extends BaseController
{
    public function get(): string
    {
        return 'ok';
    }
}
