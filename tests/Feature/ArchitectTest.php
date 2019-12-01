<?php

namespace JPeters\Architect\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use JPeters\Architect\Architect;
use JPeters\Architect\Tests\ArchitectTest as BaseArchitectTest;
use JPeters\Architect\Tests\Traits\LogsInUses;

class ArchitectTest extends BaseArchitectTest
{
    use LogsInUses;

    protected function setUp(): void
    {
        parent::setUp();

        $this->logIn();
    }

    /** @test */
    public function it_shows_the_core_javascript_in_the_view()
    {
        $this->get('/architect')
            ->assertSee(json_encode(Architect::coreJavascript()));
    }
}
