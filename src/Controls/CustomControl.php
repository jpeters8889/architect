<?php

namespace JPeters\Architect\Controls;

use Illuminate\Http\Request;
use JPeters\Architect\Blueprints\Blueprint;

abstract class CustomControl extends Control
{
    abstract public function handleRequest(Request $request, Blueprint $blueprint);
}