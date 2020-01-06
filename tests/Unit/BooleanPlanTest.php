<?php

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\Plans\Boolean;
use JPeters\Architect\Tests\Abstracts\PlanTestCase;
use JPeters\Architect\Tests\Traits\TestsPlanRelationships;

class BooleanPlanTest extends PlanTestCase
{
    use TestsPlanRelationships;

    public function getPlan()
    {
        return Boolean::class;
    }

    public function getColumnName()
    {
        return 'boolean';
    }
}
