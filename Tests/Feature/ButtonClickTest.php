<?php

namespace JPeters\Architect\Tests\Feature;

use JPeters\Architect\Blueprints\Blueprint;
use JPeters\Architect\Plans\Button;
use JPeters\Architect\Tests\ArchitectTestCase;
use JPeters\Architect\Tests\Laravel\Models\User;
use JPeters\Architect\Tests\Traits\LogsInUses;

class ButtonClickTest extends ArchitectTestCase
{
    use LogsInUses;

    /** @var User */
    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->logIn();

        $this->architect->registerBlueprint(new class extends Blueprint {
            public function blueprintRoute()
            {
                return 'blueprint';
            }

            public function model(): string
            {
                return User::class;
            }

            public function plans(): array
            {
                return [
                    (new Button('click'))
                        ->onClick(static function (User $user) {
                            $user->update(['name' => 'Updated']);
                        }),
                ];
            }
        });

        $this->user = factory(User::class)->create();
    }

    /** @test */
    public function it_errors_when_we_dont_pass_a_blueprint_to_the_request()
    {
        $this->makeRequest(['blueprint' => null])->assertSessionHasErrors('blueprint');
    }

    /** @test */
    public function it_errors_when_we_dont_pass_a_button_to_the_request()
    {
        $this->makeRequest(['button' => null])->assertSessionHasErrors('button');
    }

    /** @test */
    public function it_errors_when_we_dont_pass_a_id_to_the_request()
    {
        $this->makeRequest(['id' => null])->assertSessionHasErrors('id');
    }

    /** @test */
    public function it_errors_when_we_pass_an_invalid_blueprint()
    {
        $this->makeRequest(['blueprint' => 'foo'])->assertStatus(400);
    }

    /** @test */
    public function it_errors_when_we_pass_an_invalid_button()
    {
        $this->makeRequest(['button' => 'foo'])->assertStatus(400);
    }

    /** @test */
    public function it_resolves_a_button()
    {
        $request = $this->makeRequest();

        $request->assertStatus(200);

        $this->assertEquals('Updated', $this->user->fresh()->name);
    }

    protected function makeRequest($params = [])
    {
        return $this->post('/architect/api/button', array_merge([
            'blueprint' => 'blueprint',
            'button' => 'click',
            'id' => $this->user->id,
        ], $params));
    }
}
