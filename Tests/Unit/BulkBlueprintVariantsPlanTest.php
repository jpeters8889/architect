<?php

declare(strict_types=1);

namespace JPeters\Architect\Tests\Unit;

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use JPeters\Architect\Plans\BulkBlueprintVariants;
use JPeters\Architect\Plans\Plan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use JPeters\Architect\Plans\Group;
use JPeters\Architect\Plans\Boolean;
use JPeters\Architect\Plans\Textfield;
use Illuminate\Foundation\Testing\WithFaker;
use JPeters\Architect\TestHelpers\Laravel\Models\Blog;
use JPeters\Architect\TestHelpers\Laravel\Models\User;
use JPeters\Architect\TestHelpers\Abstracts\PlanTestCase;

class BulkBlueprintVariantsPlanTest extends PlanTestCase
{
    use WithFaker;

    /** @var BulkBlueprintVariants */
    protected $plan;

    /**
     * @var array
     */
    private $setPlans;

    protected function setUp(): void
    {
        parent::setUp();

        $this->makeFaker('en_GB');

        $this->plan->plans($this->setPlans = [
            new Textfield('name'),
            new Textfield('password'),
        ]);
    }

    public function getPlan()
    {
        return BulkBlueprintVariants::class;
    }

    public function getColumnName()
    {
        return 'Bulk';
    }

    /** @test */
    public function itCanHavePlansSet()
    {
        $this->assertInstanceOf(Collection::class, $this->plan->getPlans());

        $this->plan->getPlans()->each(function ($plan, $index) {
            $this->assertEquals($this->setPlans[$index]->getLabel(), $plan['label']);
            $this->assertEquals($this->setPlans[$index]->getColumn(), $plan['name']);
            $this->assertEquals($this->setPlans[$index]->vuePrefix().'-form', $plan['component']);
            $this->assertEquals($this->setPlans[$index]->getMetas(), $plan['metas']);
            $this->assertNull($plan['value']);
        });
    }

    /** @test */
    public function itReturnsPlansInTheMetas()
    {
        $this->assertArrayHasKey('plans', $this->plan->getMetas());
    }

    /** @test */
    public function itUpdatesTheModel()
    {
        $user = factory(User::class)->create();

        $this->plan->handleUpdate($user, $this->getColumnName(), ['name' => 'Name updated', 'password' => 'password updated']);

        $this->assertEquals('Name updated', $user->name);
        $this->assertFalse(Hash::check('password', $user->password));
    }
}
