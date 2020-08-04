<?php

declare(strict_types=1);

namespace JPeters\Architect\Tests\Feature;

use JPeters\Architect\TestHelpers\ArchitectTestCase;
use JPeters\Architect\TestHelpers\Traits\LogsInUsers;

class AssetTest extends ArchitectTestCase
{
    use LogsInUsers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->logIn();

        $this->architect->assetManager->registerStyle('testStylesheet', __DIR__.'/../Assets/style.css');
        $this->architect->assetManager->registerScript('testScript', __DIR__.'/../Assets/javascript.js');
    }

    /** @test */
    public function it_loads_the_css_file()
    {
        $request = $this->get('/architect/api/assets/style/testStylesheet')
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'text/css; charset=UTF-8');

        $this->assertStringEqualsFile(__DIR__.'/../Assets/style.css', $request->content());
    }

    /** @test */
    public function it_loads_the_javascript_file()
    {
        $request = $this->get('/architect/api/assets/script/testScript')
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/javascript');

        $this->assertStringEqualsFile(__DIR__.'/../Assets/javascript.js', $request->content());
    }
}
