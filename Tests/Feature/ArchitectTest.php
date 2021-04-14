<?php

declare(strict_types=1);

namespace JPeters\Architect\Tests\Feature;

use JPeters\Architect\Architect;
use JPeters\Architect\TestHelpers\Traits\LogsInUsers;
use JPeters\Architect\TestHelpers\ArchitectTestCase as BaseArchitectTest;

class ArchitectTest extends BaseArchitectTest
{
    use LogsInUsers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->logIn();
    }

    /** @test */
    public function itShowsTheCoreJavascriptInTheView()
    {
        $this->withoutExceptionHandling();
        $this->get('/architect')
            ->assertSee(json_encode(Architect::coreJavascript($this->architect->blueprintManager)), false);
    }
}
