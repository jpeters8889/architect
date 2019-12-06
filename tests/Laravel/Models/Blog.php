<?php

namespace JPeters\Architect\Tests\Laravel\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    public function attributes()
    {
        return $this->hasMany(BlogAttribute::class);
    }
}
