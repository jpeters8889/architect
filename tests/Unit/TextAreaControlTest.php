<?php

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\Controls\Textarea;
use JPeters\Architect\Tests\Abstracts\ControlTest;

class TextAreaControlTest extends ControlTest
{
    /** @var Textarea */
    protected $control;

    public function getControl()
    {
        return Textarea::class;
    }

    public function getColumnName()
    {
        return 'textarea';
    }

    /** @test */
    public function it_can_set_the_number_of_rows()
    {
        $this->assertEquals(['rows' => 3], $this->control->getMetas());

        $this->control->rows($rows = 10);

        $this->assertEquals(['rows' => $rows], $this->control->getMetas());
    }
}
