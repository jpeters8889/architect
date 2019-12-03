<?php

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\Plans\Boolean;
use JPeters\Architect\Tests\Abstracts\PlanTest;

class BooleanPlanTest extends PlanTest
{
    public function getPlan()
    {
        return Boolean::class;
    }

    public function getColumnName()
    {
        return 'boolean';
    }
}
