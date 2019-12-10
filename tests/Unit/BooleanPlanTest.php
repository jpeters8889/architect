<?php

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\Plans\Boolean;
use JPeters\Architect\Tests\Abstracts\PlanTestCase;

class BooleanPlanTest extends PlanTestCase
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
