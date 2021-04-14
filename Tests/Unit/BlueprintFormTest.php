<?php

declare(strict_types=1);

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\Plans\Plan;
use JPeters\Architect\TestHelpers\ArchitectTestCase;
use JPeters\Architect\Blueprints\BlueprintFormExtractor;
use JPeters\Architect\TestHelpers\Laravel\Blueprints\User;
use JPeters\Architect\TestHelpers\Laravel\Models\User as UserModel;

class BlueprintFormTest extends ArchitectTestCase
{
    /**
     * @var BlueprintFormExtractor
     */
    private $form;

    protected function setUp(): void
    {
        parent::setUp();

        $this->form = new BlueprintFormExtractor(new User());
    }

    /** @test */
    public function itCreatesAListWithTheCorrectKeys()
    {
        $keys = ['plans', 'meta', 'vue-suffix'];

        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, $this->form->make());
        }
    }

    /** @test */
    public function itContainsTheMetaInformation()
    {
        $this->assertEquals(['title' => 'Users'], $this->form->make()['meta']);
    }

    /** @test */
    public function itShowsTheVueSuffix()
    {
        $this->assertEquals('list', $this->form->make()['vue-suffix']);
    }

    /** @test */
    public function itReturnsAnArrayOfPlans()
    {
        $this->assertIsArray($this->form->make()['plans']);
    }

    /** @test */
    public function itDisplaysThePlansInTheCorrectFormat()
    {
        $keys = ['component', 'label', 'metas', 'name', 'value'];

        foreach ($this->form->make()['plans'] as $plan) {
            foreach ($keys as $key) {
                $this->assertArrayHasKey($key, $plan);
            }
        }
    }

    /** @test */
    public function itDoesntDisplayAPlanThatIsHiddenFromForms()
    {
        $this->assertNotContains('updates_at', $this->form->make()['plans']);
    }

    /** @test */
    public function itDisplaysAllOfThePlansInTheBlueprint()
    {
        $blueprint = new User();
        $plans = $this->form->make()['plans'];

        foreach ($blueprint->plans() as $index => $currentPlan) {
            /** @var Plan $currentPlan */
            if (!$currentPlan->isAvailableOnForm()) {
                continue;
            }

            $plan = $plans[$index];

            $this->assertEquals($currentPlan->vuePrefix().'-form', $plan['component']);
            $this->assertEquals($currentPlan->getLabel(), $plan['label']);
            $this->assertEquals($currentPlan->getColumn(), $plan['name']);
        }
    }

    /** @test */
    public function itLoadsPlansWithAValueWhenLoadingTheEditForm()
    {
        /** @var UserModel $userModel */
        $userModel = factory(UserModel::class)->create();

        $this->form->getValuesFrom($userModel->id)->make();

        $blueprint = new User();
        $plans = $this->form->make()['plans'];

        foreach ($blueprint->plans() as $index => $currentPlan) {
            /** @var Plan $currentPlan */
            if (!$currentPlan->isAvailableOnForm()) {
                continue;
            }

            $plan = $plans[$index];

            $this->assertEquals($userModel->{$currentPlan->getColumn()}, $plan['value']);
        }
    }
}
