<?php

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\Plans\Textfield;
use JPeters\Architect\Tests\Abstracts\PlanTest;

class TextFieldPlanTest extends PlanTest
{
    public function getPlan()
    {
        return Textfield::class;
    }

    public function getColumnName()
    {
        return 'textfield';
    }
}
