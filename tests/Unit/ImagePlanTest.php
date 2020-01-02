<?php

namespace JPeters\Architect\Tests\Unit;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use JPeters\Architect\Plans\Image;
use JPeters\Architect\Plans\Plan;
use JPeters\Architect\Tests\Abstracts\PlanTestCase;
use JPeters\Architect\Tests\Laravel\Models\User;

class ImagePlanTest extends PlanTestCase
{
    public function getPlan()
    {
        return Image::class;
    }

    public function getColumnName()
    {
        return 'image';
    }

    /** @test */
    public function it_can_be_marked_as_being_from_a_relationship()
    {
        $this->markTestSkipped();
    }

    /** @test */
    public function it_updates_a_plan_when_marked_as_in_a_relationship()
    {
        $this->markTestSkipped();
    }

    /** @test */
    public function it_updates_the_model()
    {
        Storage::fake();

        /** @var User $user */
        $user = factory(User::class)->create();

        $class = $this->getPlan();

        /** @var Image $plan */
        $plan = new $class('image');

        $image = UploadedFile::fake()->image('foo.jpg');

        $this->assertNull($user->image);
        $plan->handleUpdate($user, 'image', $image);

        $this->assertNotNull($user->image);
        Storage::assertExists($user->image);
    }
}
