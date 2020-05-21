<?php

namespace JPeters\Architect;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
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

        $this->endpoints[$method][$route.'/'.$function] = [
            'class' => $class,
            'function' => $function,
        ];
    }

    public function checkEndpointExists($method, $route)
    {
        $this->validateMethod($method);
        $this->validateRoute($route);

        return isset($this->endpoints[$method][$route]);
    }

    public function getEndpointDetails($method, $route)
    {
        $this->validateMethod($method);
        $this->validateRoute($route);

        $this->checkEndpointIsRegistered($method, $route);

        return $this->endpoints[$method][$route];
    }

    public function loadEndpoint($method, $route, Request $request, $id = null)
    {
        $this->validateMethod($method);
        $this->validateRoute($route);

        $this->checkEndpointIsRegistered($method, $route);

        $endpoint = $this->endpoints[$method][$route];

        $class = $endpoint['class'];
        $function = $endpoint['function'];

        try {
            $reflectedMethod = new \ReflectionMethod($class, $function);

            $dependencies = $reflectedMethod->getParameters();

            return (new $class())->$function(...$this->getDependencies($dependencies, $request, $id));
        } catch (\Exception $exception) {
            throw new RuntimeException('Unable to execute endpoint, ' . $exception->getMessage());
        }
    }

    private function getDependencies($dependencies, Request $request, $id = null)
    {
        $bootableDependencies = [];

        foreach ($dependencies as $dependency) {
            /** @var ReflectionParameter $dependency */
            if (! $typeHint = $dependency->getType()) {
                continue;
            }

            if ($typeHint === Request::class) {
                $bootableDependencies[] = $request;
                continue;
            }

            $bootableDependencies[] = resolve($typeHint->getName());
        }

        $bootableDependencies[] = $id;

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

    /**
     * @param $route
     */
    protected function validateRoute(&$route): void
    {
        if (!Str::contains($route, '/')) {
            $route .= '/handle';
        }
    }
}
