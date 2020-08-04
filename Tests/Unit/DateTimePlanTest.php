<?php

declare(strict_types=1);

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\Plans\DateTime;
use JPeters\Architect\TestHelpers\Abstracts\PlanTestCase;
use JPeters\Architect\TestHelpers\Traits\TestsPlanRelationships;

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
