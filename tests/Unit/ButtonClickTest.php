<?php

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\Blueprints\Blueprint;
use JPeters\Architect\Plans\Button;
use JPeters\Architect\Tests\ArchitectTestCase;
use JPeters\Architect\Tests\Laravel\Models\User;

class ButtonClickTest extends ArchitectTestCase
{
    /** @var User */
    private $user;

    protected function setUp(): void
    {
        parent::setUp();

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
    public function it_handles_the_click_event()
    {
        $this->assertNotEquals('Updated', $this->user->name);

        $this->architect->button->handleClickForButton('click', 'blueprint', $this->user->id);

        $this->assertEquals('Updated', $this->user->fresh()->name);
    }
}
