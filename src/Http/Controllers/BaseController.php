<?php

namespace JPeters\Architect\Http\Controllers;

use Illuminate\Routing\Controller;
use JPeters\Architect\Architect;

abstract class BaseController extends Controller
{
    /**
     * @var Architect
     */
    protected $architect;

    public function __construct(Architect $architect)
    {
        $this->architect = $architect;
    }
}
