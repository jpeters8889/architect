<?php

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\Plans\DateTime;
use JPeters\Architect\Tests\Abstracts\PlanTestCase;
use JPeters\Architect\Tests\Traits\TestsPlanRelationships;

class DateTimePlanTest extends PlanTestCase
{
    use TestsPlanRelationships;

    public function getPlan()
    {
        return DateTime::class;
    }

    public function getColumnName()
    {
        return 'date';
    }
}
