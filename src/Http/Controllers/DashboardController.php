<?php

declare(strict_types=1);

namespace JPeters\Architect\Http\Controllers;

use RuntimeException;

class DashboardController extends BaseController
{
    public function get($dashboard): array
    {
        try {
            return $this->architect->dashboardManager->resolve($dashboard);
        } catch (RuntimeException $exception) {
            abort(404);
        }
    }
}
