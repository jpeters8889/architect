<?php

declare(strict_types=1);

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\TestHelpers\ArchitectTestCase;

class AssetManagerTest extends ArchitectTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->architect->assetManager->registerStyle('testStylesheet', __DIR__.'/../Assets/style.css');
        $this->architect->assetManager->registerScript('testScript', __DIR__.'/../Assets/javascript.js');
    }

    /** @test */
    public function itRegistersTheAssets()
    {
        $this->assertCount(1, $this->architect->assetManager->styles());
        $this->assertCount(1, $this->architect->assetManager->scripts());
    }

    /** @test */
    public function itReturnsAStyleAsset()
    {
        $this->assertEquals(
            __DIR__.'/../Assets/style.css',
            $this->architect->assetManager->findAsset('style', 'testStylesheet')
        );
    }

    /** @test */
    public function itReturnsAScriptAsset()
    {
        $this->assertEquals(
            __DIR__.'/../Assets/javascript.js',
            $this->architect->assetManager->findAsset('script', 'testScript')
        );
    }
}
