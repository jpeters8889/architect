<?php

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\Controls\Body;
use JPeters\Architect\Tests\Abstracts\ControlTest;

class BodyControlTest extends ControlTest
{
    public function getControl()
    {
        return Body::class;
    }

    public function getColumnName()
    {
        return 'body';
    }

    /** @test */
    public function it_returns_the_number_of_rows_in_the_metas()
    {
        $this->assertEquals(['rows' => 20], $this->control->getMetas());
    }
}
