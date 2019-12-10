<?php

namespace JPeters\Architect\Tests\Feature;

use JPeters\Architect\Tests\ArchitectTestCase;
use JPeters\Architect\Tests\Laravel\Blueprints\User;
use JPeters\Architect\Tests\Laravel\Models\User as UserModel;
use JPeters\Architect\Tests\Traits\LogsInUses;

class BlueprintEditFormTest extends ArchitectTestCase
{
    use LogsInUses;

    /**
     * @var UserModel
     */
    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->logIn();

        $this->user = UserModel::query()->first();

        $this->architect->registerBlueprint(User::class);
    }

    /** @test */
    public function it_loads_the_edit_page()
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
        return $this->get('/architect/api/blueprints/user/' . $this->user->id . $extra);
    }
}
