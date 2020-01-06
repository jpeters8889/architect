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

    public function vuePrefix()
    {
        return 'button';
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
}
