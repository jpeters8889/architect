<?php

namespace JPeters\Architect;

use Illuminate\Http\Request;
use ReflectionParameter;
use RuntimeException;

class ApiManager
{
    protected $endpoints = [
        'get' => [],
        'post' => [],
        'patch' => [],
        'put' => [],
        'delete' => [],
    ];

    public function registerEndpoint($method, $route, $class, $function = 'handle')
    {
        $this->validateMethod($method);

        $this->endpoints[$method][$route] = [
            'class' => $class,
            'function' => $function,
        ];
    }

    public function checkEndpointExists($method, $route)
    {
        $this->validateMethod($method);

        return isset($this->endpoints[$method][$route]);
    }

    public function getEndpointDetails($method, $route)
    {
        $this->validateMethod($method);

        $this->checkEndpointIsRegistered($method, $route);

        return $this->endpoints[$method][$route];
    }

    public function loadEndpoint($method, $route, Request $request)
    {
        $this->validateMethod($method);

        $this->checkEndpointIsRegistered($method, $route);

        $endpoint = $this->endpoints[$method][$route];

        $class = $endpoint['class'];
        $function = $endpoint['function'];

        try {
            $reflectedMethod = new \ReflectionMethod($class, $function);

            $dependencies = $reflectedMethod->getParameters();

            return (new $class())->$function(...$this->getDependencies($dependencies, $request));
        } catch (\Exception $e) {
            throw new RuntimeException('Unable to execute endpoint');
        }
    }

    private function getDependencies($dependencies, Request $request)
    {
        $bootableDependencies = [];

        foreach ($dependencies as $dependency) {
            /** @var ReflectionParameter $dependency  */
            if ($dependency === Request::class) {
                $bootableDependencies[] = $request;
                continue;
            }

            $bootableDependencies[] = resolve($dependency->getType()->getName());
        }

        return $bootableDependencies;
    }

    /**
     * @param $method
     */
    private function validateMethod(&$method): void
    {
        $method = strtolower($method);

        if (! array_key_exists($method, $this->endpoints)) {
            throw new RuntimeException('Unknown method');
        }
    }

    /**
     * @param $method
     * @param $route
     */
    private function checkEndpointIsRegistered($method, $route): void
    {
        if (! isset($this->endpoints[$method][$route])) {
            throw new RuntimeException('External endpoint not registered');
        }
    }
}
