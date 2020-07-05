<?php

namespace JPeters\Architect\Tests\Feature;

use JPeters\Architect\Tests\ArchitectTestCase;
use JPeters\Architect\Tests\Laravel\Blueprints\Blog as BlogBlueprint;
use JPeters\Architect\Tests\Laravel\Blueprints\User;
use JPeters\Architect\Tests\Laravel\Models\Blog as BlogModel;
use JPeters\Architect\Tests\Laravel\Models\User as UserModel;
use JPeters\Architect\Tests\Traits\LogsInUsers;

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
    public function it_loads_the_list_page()
    {
        $this->makeRequest()
            ->assertStatus(200);
    }

    /** @test */
    public function it_loads_the_labels_object()
    {
        $this->makeRequest()
            ->assertJsonStructure(['labels']);
    }

    /** @test */
    public function it_shows_a_paginated_list_of_current_data()
    {
        $this->makeRequest()
            ->assertJsonFragment(['current_page' => 1]);

        $this->makeRequest('?page=2')
            ->assertJsonFragment(['current_page' => 2]);
    }

    /** @test */
    public function it_returns_a_limited_list_when_searching()
    {
        UserModel::query()->first()->update(['email' => 'jamie@jamie-peters.co.uk']);

        $this->assertNotEquals(1, $this->makeRequest()->json('data.to'));

        $this->assertEquals(1, $this->makeRequest('?search=jamie@jamie-peters.co.uk')->json('data.to'));
    }

    /** @test */
    public function it_can_be_filtered()
    {
        $firstBlog = factory(BlogModel::class)->create(['type_id' => 1]);
        $secondBlog = factory(BlogModel::class)->create(['type_id' => 2]);

        $this->architect->registerBlueprint(BlogBlueprint::class);

        $this->assertEquals(2, $this->get('/architect/api/blueprints/blog/list')->json('data.to'));

        $this->assertEquals(1, $this->get('/architect/api/blueprints/blog/list?filter[type_id]=1')->json('data.to'));
    }

    private function makeRequest($extra = '')
    {
        return $this->get('/architect/api/blueprints/user/list' . $extra);
    }
}
