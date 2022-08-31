<?php

declare(strict_types=1);

namespace JPeters\Architect\Http\Controllers;

use RuntimeException;

class DashboardController extends BaseController
{
    public function __invoke($dashboard): array
    {
        try {
            return $this->architect->dashboardManager->resolve($dashboard)->render();
        } catch (RuntimeException $exception) {
            abort(404);
        }
    }
}
