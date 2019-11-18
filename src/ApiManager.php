<?php

namespace JPeters\Architect;

use Illuminate\Http\Request;
use ReflectionParameter;

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
        if (!array_key_exists($method, $this->endpoints)) {
            abort(422, 'Unknown method');
        }

        $this->endpoints[$method][$route] = [
            'class' => $class,
            'function' => $function,
        ];
    }

    public function loadEndpoint($method, $route, Request $request)
    {
        if (!array_key_exists($method, $this->endpoints)) {
            abort(422, 'Unknown method');
        }

        if (!isset($this->endpoints[$method][$route])) {
            abort(404, 'External endpoint not registered');
        }

        $endpoint = $this->endpoints[$method][$route];

        $class = $endpoint['class'];
        $function = $endpoint['function'];

        $reflectedMethod = new \ReflectionMethod($class, $function);

        $dependencies = $reflectedMethod->getParameters();

        $bootableDependencies = [];

        foreach($dependencies as $dependency) {
            /** @var $dependency ReflectionParameter */
            if($dependency === Request::class) {
                $bootableDependencies[] = $request;
                continue;
            }

            $bootableDependencies[] = resolve($dependency->getType()->getName());
        }

        return (new $class)->$function(...$bootableDependencies);
    }
}