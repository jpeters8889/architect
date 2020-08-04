<?php

declare(strict_types=1);

namespace JPeters\Architect\Http\Controllers;

class DashboardController extends BaseController
{
    public function get(): array
    {
        return $this->architect->dashboard->build()->render();
    }
}
