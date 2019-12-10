<?php

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\Plans\DateTime;
use JPeters\Architect\Tests\Abstracts\PlanTestCase;

class DateTimePlanTest extends PlanTestCase
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
