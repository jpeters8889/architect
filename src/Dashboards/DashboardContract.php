<?php

namespace JPeters\Architect\Dashboards;

interface DashboardContract
{
    public function build(): DashboardGenerator;
}
