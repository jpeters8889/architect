<?php

declare(strict_types=1);

namespace JPeters\Architect\Dashboards;

interface DashboardContract
{
    public function build(): DashboardGenerator;
}
