<?php

namespace JPeters\Architect\Tests\Feature;

use JPeters\Architect\Tests\ArchitectTestCase;
use JPeters\Architect\Tests\Laravel\Blueprints\Blog;
use JPeters\Architect\Tests\Laravel\Models\BlogType;
use JPeters\Architect\Tests\Traits\LogsInUsers;

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
    public function it_errors_when_we_dont_pass_a_blueprint_to_the_request()
    {
        $this->makeRequest(['blueprint' => null])->assertSessionHasErrors('blueprint');
    }

    /** @test */
    public function it_errors_when_we_dont_pass_a_column_to_the_request()
    {
        $this->makeRequest(['column' => null])->assertSessionHasErrors('column');
    }

    /** @test */
    public function it_errors_when_we_dont_pass_a_value_to_the_request()
    {
        $this->makeRequest(['value' => null])->assertSessionHasErrors('value');
    }

    /** @test */
    public function it_errors_when_we_pass_an_invalid_blueprint()
    {
        $this->makeRequest(['blueprint' => 'foo'])->assertStatus(400);
    }

    /** @test */
    public function it_errors_when_we_pass_an_invalid_column()
    {
        $this->makeRequest(['column' => 'foo'])->assertStatus(400);
    }

    /** @test */
    public function it_resolves_a_value()
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
