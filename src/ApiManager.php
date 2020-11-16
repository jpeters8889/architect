<?php

declare(strict_types=1);

namespace JPeters\Architect;

use Exception;
use ReflectionMethod;
use RuntimeException;
use ReflectionParameter;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Container\Container;

class ApiManager
{
    protected array $endpoints = [
        'get' => [],
        'post' => [],
        'patch' => [],
        'put' => [],
        'delete' => [],
    ];

    public function registerEndpoint(string $method, string $route, string $class, string $function = 'handle'): void
    {
        $this->validateMethod($method);

        $this->endpoints[$method][$route.'/'.$function] = [
            'class' => $class,
            'function' => $function,
        ];
    }

    public function checkEndpointExists(string $method, string $route): bool
    {
        $this->validateMethod($method);
        $this->validateRoute($route);

        return isset($this->endpoints[$method][$route]);
    }

    public function getEndpointDetails(string $method, string $route)
    {
        $this->validateMethod($method);
        $this->validateRoute($route);

        $this->checkEndpointIsRegistered($method, $route);

        return $this->endpoints[$method][$route];
    }

    public function loadEndpoint(string $method, string $route, Request $request, $id = null)
    {
        $this->validateMethod($method);
        $this->validateRoute($route);

        $this->checkEndpointIsRegistered($method, $route);

        $endpoint = $this->endpoints[$method][$route];

        $class = $endpoint['class'];
        $function = $endpoint['function'];

        try {
            $reflectedMethod = new ReflectionMethod($class, $function);

            $dependencies = $reflectedMethod->getParameters();

            return (new $class())->$function(...$this->getDependencies($dependencies, $request, $id));
        } catch (Exception $exception) {
            throw new RuntimeException('Unable to execute endpoint, '.$exception->getMessage());
        }
    }

    private function getDependencies(array $dependencies, Request $request, $id = null): array
    {
        $bootableDependencies = [];

        foreach ($dependencies as $dependency) {
            /** @var ReflectionParameter $dependency */
            if (!$typeHint = $dependency->getType()) {
                continue;
            }

            if ($typeHint === Request::class) {
                $bootableDependencies[] = $request;
                continue;
            }

            $bootableDependencies[] = Container::getInstance()->make($typeHint->getName());
        }

        $bootableDependencies[] = $id;

        return $bootableDependencies;
    }

    private function validateMethod(string &$method): void
    {
        $method = strtolower($method);

        if (!array_key_exists($method, $this->endpoints)) {
            throw new RuntimeException('Unknown method');
        }
    }

    private function checkEndpointIsRegistered(string $method, string $route): void
    {
        if (!isset($this->endpoints[$method][$route])) {
            throw new RuntimeException('External endpoint not registered');
        }
    }

    protected function validateRoute(string &$route): void
    {
        if (!Str::contains($route, '/')) {
            $route .= '/handle';
        }
    }
}
