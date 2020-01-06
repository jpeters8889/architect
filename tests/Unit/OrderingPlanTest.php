<?php

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\Plans\Ordering;
use JPeters\Architect\Tests\Abstracts\PlanTestCase;
use JPeters\Architect\Tests\Traits\TestsPlanRelationships;

class OrderingPlanTest extends PlanTestCase
{
    use TestsPlanRelationships;

    public function getPlan()
    {
        return Ordering::class;
    }

    public function getColumnName()
    {
        return 'order';
    }
}
