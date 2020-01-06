<?php

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\Plans\Textfield;
use JPeters\Architect\Tests\Abstracts\PlanTestCase;
use JPeters\Architect\Tests\Traits\TestsPlanRelationships;

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
