<?php

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\Plans\Textfield;
use JPeters\Architect\Tests\Abstracts\PlanTestCase;

class TextFieldPlanTest extends PlanTestCase
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
