<?php

declare(strict_types=1);

namespace JPeters\Architect\Plans;

use Closure;
use RuntimeException;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use JPeters\Architect\Traits\TogglesVisibility;

abstract class Plan
{
    use TogglesVisibility;

    public bool $deferUpdate = false;

    protected ?string $label;
    protected $column;
    protected array $listeners = [];

    protected array $metas = [];

    protected array $events = ['changed'];

    protected ?string $relationship;

    protected ?string $default;

    public function __construct($column, $label = null)
    {
        $this->column = $column;

        if (!$label) {
            $label = str_replace('_', ' ', Str::title($column));
        }

        $this->label = $label;

        $this->bootstrapEvents();
    }

    public static function generate(...$args): Plan
    {
        return new static(...$args);
    }

    public function isInRelationship($relationship): self
    {
        $this->relationship = $relationship;

        return $this;
    }

    public function addListener(string $column, string $on, Closure $closure): self
    {
        if (!in_array($on, $this->events, true)) {
            throw new RuntimeException('Unknown event handler');
        }

        $this->listeners[$on][$column] = $closure;

        return $this;
    }

    protected function bootstrapEvents(): void
    {
        foreach ($this->events as $event) {
            $this->listeners[$event] = [];
        }
    }

    public function executeEvent(string $eventName, $value)
    {
        $event = last(explode('-', $eventName));
        $column = str_replace('-' . $event, '', $eventName);

        if (!isset($this->listeners[$event][$column])) {
            throw new RuntimeException("Couldn't find listener");
        }

        return call_user_func($this->listeners[$event][$column], $value);
    }

    public function getCurrentValue(Model $model)
    {
        $value = $model->{$this->getColumn()};

        if (isset($this->relationship)) {
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

    public function requestMethod(): string
    {
        return 'input';
    }

    public function hasDatabaseColumn(): bool
    {
        return true;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function getColumn()
    {
        return $this->column;
    }

    public function parseListeners(): array
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

    public function withMetas(array $metas): void
    {
        $this->metas = $metas;
    }

    public function getMetas(): array
    {
        return array_merge($this->metas, [
            'listeners' => $this->parseListeners(),
        ]);
    }

    public function getDefault(): ?string
    {
        return $this->default ?? null;
    }

    public function setDefault(string $default): self
    {
        $this->default = $default;

        return $this;
    }

    public function defer()
    {
        $this->deferUpdate = true;

        return $this;
    }

    abstract public function vuePrefix(): string;

    abstract public function handleUpdate(Model $model, $column, $value);
}
