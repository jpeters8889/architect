<?php

declare(strict_types=1);

namespace JPeters\Architect\Tests\Unit;

use RuntimeException;
use Illuminate\Http\Request;
use Illuminate\View\Factory;
use JPeters\Architect\TestHelpers\ArchitectTestCase;
use JPeters\Architect\Tests\Mocks\ApiManagerHandler;

class ApiManagerTest extends ArchitectTestCase
{
    protected $methods = ['get', 'post', 'put', 'patch', 'delete'];

    /** @test */
    public function itRegistersEndpoints()
    {
        foreach ($this->methods as $method) {
            $this->architect->apiManager->registerEndpoint($method, 'foo', ApiManagerHandler::class);

            $this->assertTrue($this->architect->apiManager->checkEndpointExists($method, 'foo'));
            $this->assertEquals(
                [
                    'class' => ApiManagerHandler::class,
                    'function' => 'handle',
                ],
                $this->architect->apiManager->getEndpointDetails($method, 'foo')
            );
        }
    }

    /** @test */
    public function itErrorsWhenTryingToRegisterAnEndpointWithAnUnknownMethod()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unknown method');

        $this->architect->apiManager->registerEndpoint('foo', 'bar', ApiManagerHandler::class);
    }

    /** @test */
    public function itRegistersWhenRegisteringAnEndpointInUppercase()
    {
        $this->architect->apiManager->registerEndpoint('GET', 'foo', ApiManagerHandler::class);

        $this->assertTrue($this->architect->apiManager->checkEndpointExists('get', 'foo'));
        $this->assertEquals(
            [
                'class' => ApiManagerHandler::class,
                'function' => 'handle',
            ],
            $this->architect->apiManager->getEndpointDetails('get', 'foo')
        );
    }

    /** @test */
    public function itReturnsFalseWhenTryingToCheckANonExistentRoute()
    {
        $this->assertFalse($this->architect->apiManager->checkEndpointExists('get', 'foo'));
    }

    /** @test */
    public function itErrorsWhenTryingToGetTheDetailsOfANonExistentEndpoint()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('External endpoint not registered');

        $this->architect->apiManager->getEndpointDetails('get', 'foo');
    }

    /** @test */
    public function itLoadsAndHandlesAnEndpoint()
    {
        $request = resolve(Request::class);
        $apiManager = new ApiManagerHandler();

        foreach ($this->methods as $method) {
            $this->architect->apiManager->registerEndpoint($method, 'foo', ApiManagerHandler::class);

            $endpoint = $this->architect->apiManager->loadEndpoint($method, 'foo', $request);

            $this->assertEquals($apiManager->handle(), $endpoint);
        }
    }

    /** @test */
    public function itLoadsAnEndpointWithDependencies()
    {
        $this->architect->apiManager->registerEndpoint('get', 'foo', ApiManagerHandler::class, 'handleWithDependencies');

        $endpoint = $this->architect->apiManager->loadEndpoint('get', 'foo/handleWithDependencies', resolve(Request::class));

        $this->assertInstanceOf(Request::class, $endpoint[0]);
        $this->assertInstanceOf(Factory::class, $endpoint[1]);
    }

    /** @test */
    public function itErrorsWhenTryingToLoadAnUnknownMethod()
    {
        $this->architect->apiManager->registerEndpoint('get', 'foo', ApiManagerHandler::class, 'bar');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('External endpoint not registered');

        $this->architect->apiManager->loadEndpoint('get', 'foo', resolve(Request::class));
    }

    /** @test */
    public function itLoadsTwoSeperateEndpointsWhenRegisteredAsTheSameHttpMethod()
    {
        $this->architect->apiManager->registerEndpoint('get', 'foo', ApiManagerHandler::class, 'first');
        $this->architect->apiManager->registerEndpoint('get', 'foo', ApiManagerHandler::class, 'second');

        $request = resolve(Request::class);
        $apiManager = new ApiManagerHandler();

        $first = $this->architect->apiManager->loadEndpoint('get', 'foo/first', $request);
        $second = $this->architect->apiManager->loadEndpoint('get', 'foo/second', $request);

        $this->assertEquals($apiManager->first(), $first);
        $this->assertEquals($apiManager->second(), $second);
    }
}
