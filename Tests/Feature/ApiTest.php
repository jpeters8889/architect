<?php

declare(strict_types=1);

namespace JPeters\Architect\Tests\Feature;

use JPeters\Architect\TestHelpers\ArchitectTestCase;
use JPeters\Architect\Tests\Mocks\ApiManagerHandler;
use JPeters\Architect\TestHelpers\Traits\LogsInUsers;

class ApiTest extends ArchitectTestCase
{
    use LogsInUsers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->logIn();
    }

    /** @test */
    public function itLoadsAnApiEndpoint()
    {
        $this->architect->apiManager->registerEndpoint('get', 'foo', ApiManagerHandler::class, 'handle');

        $this->makeRequest()->assertStatus(200);
    }

    /** @test */
    public function itHandlesTheRequest()
    {
        $this->withoutExceptionHandling();

        $this->architect->apiManager->registerEndpoint('get', 'foo', ApiManagerHandler::class, 'handle');

        $this->makeRequest()->assertSee((new ApiManagerHandler())->handle());
    }

    private function makeRequest($extra = '')
    {
        return $this->get('/architect/api/external/foo/handle'.$extra);
    }
}
