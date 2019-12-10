<?php

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\Tests\ArchitectTestCase;

class AssetManagerTest extends ArchitectTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->architect->assetManager->registerStyle('testStylesheet', __DIR__ . '/../Assets/style.css');
        $this->architect->assetManager->registerScript('testScript', __DIR__ . '/../Assets/javascript.js');
    }

    /** @test */
    public function it_registers_the_assets()
    {
        $this->assertCount(1, $this->architect->assetManager->styles());
        $this->assertCount(1, $this->architect->assetManager->scripts());
    }

    /** @test */
    public function it_returns_a_style_asset()
    {
        $this->assertEquals(
            __DIR__ . '/../Assets/style.css',
            $this->architect->assetManager->findAsset('style', 'testStylesheet')
        );
    }

    /** @test */
    public function it_returns_a_script_asset()
    {
        $this->assertEquals(
            __DIR__ . '/../Assets/javascript.js',
            $this->architect->assetManager->findAsset('script', 'testScript')
        );
    }
}
