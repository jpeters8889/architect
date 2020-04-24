<?php

namespace JPeters\Architect\Tests\Feature;

use JPeters\Architect\Tests\ArchitectTestCase;
use JPeters\Architect\Tests\Mocks\ApiManagerHandler;
use JPeters\Architect\Tests\Traits\LogsInUses;

class ApiTest extends ArchitectTestCase
{
    use LogsInUses;

    protected function setUp(): void
    {
        parent::setUp();

        $this->logIn();

        $this->architect->apiManager->registerEndpoint('get', 'foo', ApiManagerHandler::class, 'handle');
    }

    /** @test */
    public function it_loads_an_api_endpoint()
    {
        $this->makeRequest()->assertStatus(200);
    }

    /** @test */
    public function it_handles_the_request()
    {
        $this->makeRequest()->assertSee((new ApiManagerHandler())->handle());
    }

    private function makeRequest($extra = '')
    {
        return $this->get('/architect/api/external/foo/handle' . $extra);
    }
}
