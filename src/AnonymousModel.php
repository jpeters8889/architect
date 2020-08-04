<?php

declare(strict_types=1);

namespace JPeters\Architect;

use Illuminate\Database\Eloquent\Model;

class AnonymousModel extends Model
{
    protected $guarded = [];

    public function getAttribute($key)
    {
        $parent = parent::getAttribute($key);

        if (!$parent) {
            return '0';
        }

        return $parent;
    }
}
