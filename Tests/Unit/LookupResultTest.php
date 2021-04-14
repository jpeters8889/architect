<?php

declare(strict_types=1);

namespace JPeters\Architect\Tests\Unit;

use Illuminate\Database\Eloquent\Collection;
use JPeters\Architect\TestHelpers\ArchitectTestCase;
use JPeters\Architect\TestHelpers\Laravel\Blueprints\Blog;
use JPeters\Architect\TestHelpers\Laravel\Models\BlogType;

class LookupResultTest extends ArchitectTestCase
{
    /** @var BlogType */
    private $newsType;

    /** @var BlogType */
    private $personalType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->newsType = BlogType::query()->create(['type' => 'News']);
        $this->personalType = BlogType::query()->create(['type' => 'Personal']);

        $this->architect->registerBlueprint(Blog::class);
    }

    /** @test */
    public function itResolvesTheBlueprint()
    {
        $actionResult = $this->architect->lookup->performAction('blog', 'type_id', 'Personal');

        $this->assertInstanceOf(Collection::class, $actionResult);
        $this->assertTrue($this->personalType->is($actionResult->first()));
    }
}
