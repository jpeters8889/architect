<?php

declare(strict_types=1);

namespace JPeters\ArchitectTests\Unit;

use JPeters\Architect\ArchitectGateway;
use JPeters\Architect\Models\ArchitectSetting;
use JPeters\Architect\TestHelpers\ArchitectTestCase;
use JPeters\Architect\TestHelpers\Traits\LogsInUsers;
use JPeters\Architect\TestHelpers\Laravel\Models\User;
use JPeters\Architect\TestHelpers\Laravel\ArchitectGateway as TestArchitectGateway;

class ArchitechSettingsTest extends ArchitectTestCase
{
    use LogsInUsers;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create(['email' => 'jamie@jamie-peters.co.uk']);
        $this->logIn($this->user);

        ArchitectSetting::query()->insert([
            ['user_id' => 1, 'name' => 'first-setting', 'value' => 'foobar'],
        ]);
    }

    /** @test */
    public function it_can_resolve_a_setting()
    {
        $this->assertEquals('foobar', $this->user->architectSetting('first-setting'));
    }

    /** @test */
    public function it_respects_the_default_if_the_value_isnt_found()
    {
        $this->assertEquals('bar', $this->user->architectSetting('foo', 'bar'));
    }

    /** @test */
    public function it_respects_the_architect_gateway()
    {
        config(['architect.gateway' => TestArchitectGateway::class]);
        $this->app->instance(ArchitectGateway::class, new TestArchitectGateway());

        // user has access to architect
        $this->assertEquals('foobar', $this->user->architectSetting('first-setting'));

        $this->user->update(['email' => 'foo@bar.com']);

        // user no longer has access
        $this->assertEquals('no access', $this->user->architectSetting('first-setting', 'no access'));
    }

    /** @test */
    public function it_can_set_settings()
    {
        $this->assertNull($this->user->architectSetting('new-setting'));

        $this->user->setArchitectSetting('new-setting', 'foo');

        $this->assertEquals('foo', $this->user->architectSetting('new-setting'));
    }

    /** @test */
    public function it_cant_update_settings_if_the_user_isnt_allowed_through_the_gatway()
    {
        config(['architect.gateway' => TestArchitectGateway::class]);
        $this->app->instance(ArchitectGateway::class, new TestArchitectGateway());

        $this->user->update(['email' => 'foo@bar.com']);

        $this->assertCount(1, ArchitectSetting::all());

        $this->user->setArchitectSetting('new-setting', 'foo');

        $this->assertCount(1, ArchitectSetting::all());

        $this->assertNull($this->user->architectSetting('new-setting'));
    }
}
