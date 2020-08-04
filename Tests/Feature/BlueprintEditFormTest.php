<?php

declare(strict_types=1);

namespace JPeters\Architect\Tests\Feature;

use JPeters\Architect\TestHelpers\ArchitectTestCase;
use JPeters\Architect\TestHelpers\Traits\LogsInUsers;
use JPeters\Architect\TestHelpers\Laravel\Blueprints\User;
use JPeters\Architect\TestHelpers\Laravel\Models\User as UserModel;

class BlueprintEditFormTest extends ArchitectTestCase
{
    use LogsInUsers;

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
        return $this->get('/architect/api/blueprints/user/'.$this->user->id.$extra);
    }
}
