<?php

namespace JPeters\Architect\Plans;

use Closure;
use Illuminate\Database\Eloquent\Model;

class Button extends Plan
{
    /**
     * @var Closure
     */
    private $onClickHandler;

    private $clickMethod = 'ajax';

    public function hasDatabaseColumn()
    {
        return false;
    }

    public function vuePrefix()
    {
        return 'button';
    }

    public function getMetas()
    {
        return array_merge(parent::getMetas() ?? [], [
            'click_method' => $this->clickMethod,
        ]);
    }

    public function handleUpdate(Model $model, $column = null, $value = null)
    {
        return call_user_func($this->onClickHandler, $model);
    }

    public function onClick(Closure $closure)
    {
        $this->onClickHandler = $closure;

        return $this;
    }

    public function openAsLink()
    {
        $this->clickMethod = 'link';

        return $this;
    }
}
