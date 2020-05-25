<?php

namespace JPeters\Architect\Traits;

use Illuminate\Support\Facades\Auth;
use JPeters\Architect\ArchitectGateway;

trait CheckArchitectGateway
{
    protected function validateGateway(): bool
    {
        $class = config('architect.gateway');

        if (!$class) {
            return true;
        }

        /** @var ArchitectGateway $gateway */
        $gateway = new $class();

        return $gateway->canUseArchitect(Auth::user());
    }
}
