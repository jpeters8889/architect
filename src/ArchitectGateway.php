<?php

namespace JPeters\Architect;

use Illuminate\Contracts\Auth\Authenticatable;

interface ArchitectGateway
{
    public function canUseArchitect(Authenticatable $user): bool;
}
