<?php

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\Plans\Ordering;
use JPeters\Architect\Tests\Abstracts\PlanTestCase;

class OrderingPlanTest extends PlanTestCase
{
    public function getPlan()
    {
        return Ordering::class;
    }

    public function getColumnName()
    {
        return 'order';
    }
}
