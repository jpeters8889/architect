<?php

declare(strict_types=1);

namespace JPeters\Architect\Tests\Feature;

use JPeters\Architect\TestHelpers\ArchitectTestCase;
use JPeters\Architect\TestHelpers\Traits\LogsInUsers;
use JPeters\Architect\TestHelpers\Laravel\Blueprints\User;
use JPeters\Architect\TestHelpers\Laravel\Models\Blog as BlogModel;
use JPeters\Architect\TestHelpers\Laravel\Models\User as UserModel;
use JPeters\Architect\TestHelpers\Laravel\Blueprints\Blog as BlogBlueprint;

class BlueprintListTest extends ArchitectTestCase
{
    use LogsInUsers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->logIn();

        factory(UserModel::class, 15)->create();

        $this->architect->registerBlueprint(User::class);
    }

    /** @test */
    public function itLoadsTheListPage()
    {
        $this->makeRequest()
            ->assertStatus(200);
    }

    /** @test */
    public function itLoadsTheLabelsObject()
    {
        $this->makeRequest()
            ->assertJsonStructure(['labels']);
    }

    /** @test */
    public function itShowsAPaginatedListOfCurrentData()
    {
        $this->makeRequest()
            ->assertJsonFragment(['current_page' => 1]);

        $this->makeRequest('?page=2')
            ->assertJsonFragment(['current_page' => 2]);
    }

    /** @test */
    public function itReturnsALimitedListWhenSearching()
    {
        UserModel::query()->first()->update(['email' => 'jamie@jamie-peters.co.uk']);

        $this->assertNotEquals(1, $this->makeRequest()->json('data.to'));

        $this->assertEquals(1, $this->makeRequest('?search=jamie@jamie-peters.co.uk')->json('data.to'));
    }

    /** @test */
    public function itCanBeFiltered()
    {
        $firstBlog = factory(BlogModel::class)->create(['type_id' => 1]);
        $secondBlog = factory(BlogModel::class)->create(['type_id' => 2]);

        $this->architect->registerBlueprint(BlogBlueprint::class);

        $this->assertEquals(2, $this->get('/architect/api/blueprints/blog/list')->json('data.to'));

        $this->assertEquals(1, $this->get('/architect/api/blueprints/blog/list?filter[type_id]=1')->json('data.to'));
    }

    private function makeRequest($extra = '')
    {
        return $this->get('/architect/api/blueprints/user/list'.$extra);
    }
}
