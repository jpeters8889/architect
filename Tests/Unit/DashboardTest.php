<?php

declare(strict_types=1);

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\Dashboards\Dashboard;
use JPeters\Architect\TestHelpers\ArchitectTestCase;

class DashboardTest extends ArchitectTestCase
{
    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->architect->registerDashboard(Dashboard::class);
    }

    /** @test */
    public function itRegistersDashboards()
    {
        $this->assertCount(1, $this->architect->dashboardManager->dashboardList());
    }

    /** @test */
    public function itRendersForTheNavigation()
    {
        $this->assertCount(1, $navigation = $this->architect->dashboardManager->renderForNavigation());

        foreach (['label', 'route'] as $key) {
            $this->assertArrayHasKey($key, $navigation[0]);
        }
    }

    /** @test */
    public function itResolvesADashboard()
    {
        $dashboard = $this->architect->dashboardManager->resolve('dashboard')->render();

        $this->assertArrayHasKey('title', $dashboard);
        $this->assertArrayHasKey('cards', $dashboard);
        $this->assertArrayHasKey('title', $dashboard['cards'][0]);
        $this->assertArrayHasKey('width', $dashboard['cards'][0]);
        $this->assertArrayHasKey('content', $dashboard['cards'][0]);
    }
}
