<?php

namespace JPeters\Architect\Tests\Feature;

use JPeters\Architect\Tests\ArchitectTestCase;
use JPeters\Architect\Tests\Laravel\Blueprints\User;
use JPeters\Architect\Tests\Laravel\Models\User as UserModel;
use JPeters\Architect\Tests\Traits\LogsInUses;

class BlueprintListTest extends ArchitectTestCase
{
    use LogsInUses;

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

    private function makeRequest($extra = '')
    {
        return $this->get('/architect/api/blueprints/user/list' . $extra);
    }
}
