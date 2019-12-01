<?php

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\Controls\Boolean;
use JPeters\Architect\Tests\Abstracts\ControlTest;

class BooleanControlTest extends ControlTest
{
    public function getControl()
    {
        return Boolean::class;
    }

    public function getColumnName()
    {
        return 'boolean';
    }
}
