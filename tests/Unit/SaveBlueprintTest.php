<?php

namespace JPeters\Architect\Tests\Unit;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;
use JPeters\Architect\Blueprints\SaveBlueprint;
use JPeters\Architect\Http\Requests\BlueprintSubmitRequest;
use JPeters\Architect\Tests\ArchitectTest;
use JPeters\Architect\Tests\Blueprints\User;
use JPeters\Architect\Tests\Models\User as UserModel;

class SaveBlueprintTest extends ArchitectTest
{
    use WithFaker;

    /**
     * @var BlueprintSubmitRequest
     */
    private $request;

    /**
     * @var SaveBlueprint
     */
    private $saveBlueprint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->makeFaker('en_GB');

        $this->makeRequest();
    }

    /** @test */
    public function it_saves_the_blueprint_when_adding_data()
    {
        $this->assertEmpty(UserModel::query()->get());

        $this->saveBlueprint->saveBlueprint();

        $this->assertCount(1, UserModel::query()->get());

        $user = UserModel::query()->first();

        foreach ($this->request->input() as $column => $value) {
            if (Str::startsWith($column, '_')) {
                continue;
            }

            $this->assertEquals($value, $user->$column);
        }
    }

    /** @test */
    public function it_saves_the_blueprint_when_updating_data()
    {
        $originalUser = factory(UserModel::class)->create();

        $this->makeRequest(['_state' => 'update', '_id' => $originalUser->id]);

        $this->saveBlueprint->saveBlueprint();

        $this->assertCount(1, UserModel::query()->get());

        $user = UserModel::query()->first();

        foreach ($this->request->input() as $column => $value) {
            if (Str::startsWith($column, '_')) {
                continue;
            }

            $this->assertEquals($value, $user->$column);
            $this->assertNotEquals($originalUser->$column, $user->$column);
        }
    }

    protected function makeRequest($params = []): void
    {
        $this->request = new BlueprintSubmitRequest(array_merge([
            '_blueprint' => 'user',
            '_state' => 'add',
            '_id' => null,
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'password' => Str::random(8),
            'api_token' => Str::random(80),
        ], $params));

        $this->request
            ->setContainer(app())
            ->setRedirector(resolve(Redirector::class))
            ->validateResolved();

        $this->saveBlueprint = new SaveBlueprint(new User(), $this->request);
    }
}
