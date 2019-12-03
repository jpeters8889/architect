<?php

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\Plans\DateTime;
use JPeters\Architect\Tests\Abstracts\PlanTest;

class DateTimePlanTest extends PlanTest
{
    public function getPlan()
    {
        return DateTime::class;
    }

    public function getColumnName()
    {
        return 'date';
    }
}
