<?php

declare(strict_types=1);

namespace JPeters\Architect\Http\Controllers;

use JPeters\Architect\Architect;
use Illuminate\Routing\Controller;

abstract class BaseController extends Controller
{
    protected Architect $architect;

    public function __construct(Architect $architect)
    {
        $this->architect = $architect;
    }
}
