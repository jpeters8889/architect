<?php

declare(strict_types=1);

namespace JPeters\Architect\Traits;

use Illuminate\Database\Eloquent\Model;
use JPeters\Architect\Models\ArchitectSetting;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin Model
 */
trait HasArchitectSettings
{
    protected function architectSettings(): HasMany
    {
        return $this->hasMany(ArchitectSetting::class, 'user_id');
    }

    public function architectSetting($setting, $default = null)
    {
        if (!$this->userCanUseArchitect()) {
            return $default;
        }

        if ($check = $this->architectSettings()->where('name', $setting)->first()) {
            return $check->value;
        }

        return $default;
    }

    public function setArchitectSetting($setting, $value): void
    {
        if (!$this->userCanUseArchitect()) {
            return;
        }

        $this->architectSettings()->updateOrCreate(
            ['name' => $setting],
            ['value' => $value],
        );
    }

    protected function userCanUseArchitect(): bool
    {
        if (!config('architect.gateway')) {
            return true;
        }

        return resolve(config('architect.gateway'))->canUseArchitect($this);
    }
}
