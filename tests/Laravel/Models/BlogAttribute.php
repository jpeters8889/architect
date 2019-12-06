<?php

namespace JPeters\Architect\Tests\Laravel\Models;

use Illuminate\Database\Eloquent\Model;

class BlogAttribute extends Model
{
    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
}
