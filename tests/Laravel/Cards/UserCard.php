<?php

namespace JPeters\Architect\Tests\Laravel\Cards;

use JPeters\Architect\Cards\Card;

class UserCard extends Card
{
    public function vueComponent(): string
    {
        return 'user-card';
    }
}
