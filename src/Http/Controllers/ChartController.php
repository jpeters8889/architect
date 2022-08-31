<?php

declare(strict_types=1);

namespace JPeters\Architect\Http\Controllers;

use JPeters\Architect\Dashboards\AbstractDashboard;
use JPeters\Architect\Dashboards\Cards\Card;
use JPeters\Architect\Dashboards\Cards\Chart;
use RuntimeException;

class ChartController extends BaseController
{
    public function __invoke($dashboard, $slug): array
    {
        try {
            /** @var AbstractDashboard $dashboardClass */
            $dashboardClass = $this->architect->dashboardManager->resolve($dashboard);

            /** @var Chart $chart */
            $chart = collect($dashboardClass->cards())
                ->filter(fn(Card $card) => $card->type() === 'chart')
                ->filter(fn(Chart $chart) => $chart->slug() === $slug)
                ->first();

            if (!$chart) {
                throw new RuntimeException('Chart not found');
            }

            return $chart->draw();
        } catch (RuntimeException $exception) {
            abort(404);
        }
    }
}
