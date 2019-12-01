<?php

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\Controls\DateTime;
use JPeters\Architect\Tests\Abstracts\ControlTest;

class DateTimeControlTest extends ControlTest
{
    public function getControl()
    {
        return DateTime::class;
    }

    public function getColumnName()
    {
        return 'date';
    }
}
