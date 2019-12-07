<?php

namespace JPeters\Architect\Tests\Laravel\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    public function attributes()
    {
        return $this->hasMany(BlogAttribute::class);
    }

    public function tags()
    {
        return $this->belongsToMany(BlogTag::class, 'blog_assigned_tags', 'blog_id', 'tag_id');
    }
}
