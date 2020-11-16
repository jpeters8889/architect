<?php

declare(strict_types=1);

namespace JPeters\Architect\Models;

use Illuminate\Database\Eloquent\Model;

class ArchitectSetting extends Model
{
    protected $fillable = ['name', 'value'];
}
