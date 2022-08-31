<?php

declare(strict_types=1);

namespace JPeters\Architect\Http\Controllers;

class HealthController extends BaseController
{
    public function __invoke(): string
    {
        return 'ok';
    }
}
