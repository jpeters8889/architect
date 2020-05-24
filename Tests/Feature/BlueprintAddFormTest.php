<?php

namespace JPeters\Architect\Tests\Feature;

use JPeters\Architect\Tests\ArchitectTestCase;
use JPeters\Architect\Tests\Laravel\Blueprints\User;
use JPeters\Architect\Tests\Traits\LogsInUsers;

class BlueprintAddFormTest extends ArchitectTestCase
{
    use LogsInUsers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->logIn();

        $this->architect->registerBlueprint(User::class);
    }

    /** @test */
    public function it_loads_the_add_page()
    {
        $this->makeRequest()
            ->assertStatus(200);
    }

    /** @test */
    public function it_loads_an_array_of_plans()
    {
        $request = $this->makeRequest();

        $this->assertIsArray($request->json('plans'));
    }

    private function makeRequest($extra = '')
    {
        return $this->get('/architect/api/blueprints/user/add' . $extra);
    }
}
