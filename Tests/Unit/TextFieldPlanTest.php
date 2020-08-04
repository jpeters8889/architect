<?php

declare(strict_types=1);

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\Plans\Textfield;
use JPeters\Architect\TestHelpers\Abstracts\PlanTestCase;
use JPeters\Architect\TestHelpers\Traits\TestsPlanRelationships;

class TextFieldPlanTest extends PlanTestCase
{
    use TestsPlanRelationships;

    public function getPlan()
    {
        return Textfield::class;
    }

    public function getColumnName()
    {
        return 'textfield';
    }
}
