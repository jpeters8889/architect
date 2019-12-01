<?php

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\Controls\Textfield;
use JPeters\Architect\Tests\Abstracts\ControlTest;

class TextFieldControlTest extends ControlTest
{
    public function getControl()
    {
        return Textfield::class;
    }

    public function getColumnName()
    {
        return 'textfield';
    }
}
