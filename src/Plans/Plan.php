<?php

namespace JPeters\Architect\Plans;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use JPeters\Architect\Traits\TogglesVisibility;
use RuntimeException;

abstract class Plan
{
    use TogglesVisibility;

    public $deferUpdate = false;

    protected $label;
    protected $column;
    protected $listeners = [];

    protected $metas = [];

    protected $events = ['changed'];

    protected $relationship = false;

    public function __construct($column, $label = null)
    {
        $this->column = $column;

        if (! $label) {
            $label = str_replace('_', ' ', Str::title($column));
        }

        $this->label = $label;

        $this->bootstrapEvents();
    }

    public function isInRelationship($relationship)
    {
        $this->relationship = $relationship;

        return $this;
    }

    public function addListener($column, $on, Closure $closure)
    {
        if (! in_array($on, $this->events)) {
            throw new RuntimeException('Unknown event handler');
        }

        $this->listeners[$on][$column] = $closure;

        return $this;
    }

    protected function bootstrapEvents()
    {
        foreach ($this->events as $event) {
            $this->listeners[$event] = [];
        }
    }

    public function executeEvent($eventName, $value)
    {
        $event = last(explode('-', $eventName));
        $column = str_replace('-' . $event, '', $eventName);

        if (! isset($this->listeners[$event][$column])) {
            throw new RuntimeException("Couldn't find listener");
        }

        return call_user_func($this->listeners[$event][$column], $value);
    }

    public function getCurrentValue(Model $model)
    {
        $value = $model->{$this->getColumn()};

        if ($this->relationship) {
            $bits = explode('_', $this->column);
            array_pop($bits);

            $value = $model;

            foreach ($bits as $bit) {
                $value = $value->$bit;
            }

            $value = $value->{$this->relationship};
        }

        return $value;
    }

    public function hasDatabaseColumn()
    {
        return true;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getColumn()
    {
        return $this->column;
    }

    public function parseListeners()
    {
        $listeners = [];

        foreach ($this->listeners as $event => $listener) {
            $listeners[$event] = [];
            foreach (array_keys($listener) as $column) {
                $listeners[$event] = $column;
            }
        }

        return $listeners;
    }

    public function withMetas($metas)
    {
        $this->metas = $metas;
    }

    public function getMetas()
    {
        return array_merge($this->metas, [
            'listeners' => $this->parseListeners(),
        ]);
    }

    abstract public function vuePrefix();

    abstract public function handleUpdate(Model $model, $column, $value);
}
