<?php

declare(strict_types=1);

namespace JPeters\Architect\Traits;

use Illuminate\Container\Container;
use JPeters\Architect\ArchitectGateway;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

trait CheckArchitectGateway
{
    protected function validateGateway(): bool
    {
        $class = Container::getInstance()->make(ConfigRepository::class)->get('architect.gateway');

        if (!$class) {
            return true;
        }

        /** @var ArchitectGateway $gateway */
        $gateway = new $class();

        return $gateway->canUseArchitect(Container::getInstance()->make(Authenticatable::class));
    }
}
