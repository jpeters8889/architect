<?php

namespace JPeters\Architect\Dashboards;

use Illuminate\Support\Collection;
use RuntimeException;

class DashboardManager
{
    private array $dashboards = [];

    public function registerDashboard($dashboard): void
    {
        $this->dashboards[] = $dashboard;
    }

    public function dashboardList(): array
    {
        return $this->dashboards;
    }

    public function renderForNavigation(): array
    {
        return (new Collection($this->dashboards))
            ->transform(function ($dashboard) {
                /** @var AbstractDashboard $resolvedDashboard */
                $resolvedDashboard = new $dashboard();

                return [
                    'label' => $resolvedDashboard->dashboardName(),
                    'route' => $resolvedDashboard->dashboardRoute(),
                ];
            })->toArray();
    }

    public function resolve($dashboard): array
    {
        /** @var AbstractDashboard $resolvedDashboard */
        $resolvedDashboard = (new Collection($this->dashboards))
            ->map(fn ($dashboard) => new $dashboard())
            ->filter(function (AbstractDashboard $abstractDashboard) use ($dashboard) {
                return $abstractDashboard->dashboardRoute() === $dashboard;
            })
            ->first();

        if (!$resolvedDashboard) {
            throw new RuntimeException('Dashboard not found');
        }

        return $resolvedDashboard->render();
    }
}
