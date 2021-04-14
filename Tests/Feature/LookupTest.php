<?php

declare(strict_types=1);

namespace JPeters\Architect\Tests\Feature;

use JPeters\Architect\TestHelpers\ArchitectTestCase;
use JPeters\Architect\TestHelpers\Traits\LogsInUsers;
use JPeters\Architect\TestHelpers\Laravel\Blueprints\Blog;
use JPeters\Architect\TestHelpers\Laravel\Models\BlogType;

class LookupTest extends ArchitectTestCase
{
    use LogsInUsers;

    /** @var BlogType */
    private $newsType;

    /** @var BlogType */
    private $personalType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->logIn();

        $this->newsType = BlogType::query()->create(['type' => 'News']);
        $this->personalType = BlogType::query()->create(['type' => 'Personal']);

        $this->architect->registerBlueprint(Blog::class);
    }

    /** @test */
    public function itErrorsWhenWeDontPassABlueprintToTheRequest()
    {
        $this->makeRequest(['blueprint' => null])->assertSessionHasErrors('blueprint');
    }

    /** @test */
    public function itErrorsWhenWeDontPassAColumnToTheRequest()
    {
        $this->makeRequest(['column' => null])->assertSessionHasErrors('column');
    }

    /** @test */
    public function itErrorsWhenWeDontPassAValueToTheRequest()
    {
        $this->makeRequest(['value' => null])->assertSessionHasErrors('value');
    }

    /** @test */
    public function itErrorsWhenWePassAnInvalidBlueprint()
    {
        $this->makeRequest(['blueprint' => 'foo'])->assertStatus(400);
    }

    /** @test */
    public function itErrorsWhenWePassAnInvalidColumn()
    {
        $this->makeRequest(['column' => 'foo'])->assertStatus(400);
    }

    /** @test */
    public function itResolvesAValue()
    {
        $request = $this->makeRequest();

        $request->assertStatus(200);

        $this->assertEquals($this->personalType->toArray(), $request->json(0));
    }

    protected function makeRequest($params = [])
    {
        return $this->post('/architect/api/lookup', array_merge([
            'blueprint' => 'blog',
            'column' => 'type_id',
            'value' => 'Personal',
        ], $params));
    }
}
