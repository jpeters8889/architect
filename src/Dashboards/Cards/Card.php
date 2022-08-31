<?php

declare(strict_types=1);

namespace JPeters\Architect\Dashboards\Cards;

use Closure;
use Illuminate\Support\Str;

class Card
{
    protected $content;
    protected string $title;
    protected string $width = 'full';
    protected string $type = 'card';

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    /**
     * @param string|Closure $content
     *
     * @return $this
     */
    public function setContent($content): self
    {
        $this->content = $content;

        return $this;
    }

    public static function generate(string $title): static
    {
        return new static($title);
    }

    public function setWidth($width): self
    {
        $this->width = $width;

        return $this;
    }

    public function render(): array
    {
        return [
            'title' => $this->title,
            'type' => $this->type,
            'width' => $this->width,
            'content' => $this->renderContent(),
        ];
    }

    protected function renderContent()
    {
        return $this->content instanceof Closure ? call_user_func($this->content) : $this->content;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function slug(): string
    {
        return Str::slug($this->title);
    }
}
