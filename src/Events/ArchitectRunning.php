<?php

namespace JPeters\Architect\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;

class ArchitectRunning
{
    use Dispatchable;

    /** @var Request */
    public $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}