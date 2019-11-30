<?php

namespace JPeters\Architect\Tests\Laravel;

class Mix extends \Illuminate\Foundation\Mix
{
    public function __invoke($path, $manifestDirectory = '')
    {
        $manifestDirectory = '';

        return parent::__invoke($path, $manifestDirectory);
    }
}
