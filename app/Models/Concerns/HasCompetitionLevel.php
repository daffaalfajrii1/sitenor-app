<?php

namespace App\Models\Concerns;

use App\Models\Prestasi;

trait HasCompetitionLevel
{
    public static function levelOptions(): array
    {
        return Prestasi::levelOptions();
    }

    public function getLevelLabelAttribute(): string
    {
        if (! $this->level) {
            return '—';
        }

        return static::levelOptions()[$this->level] ?? $this->level;
    }

    public static function normalizeLevel(?string $level): ?string
    {
        return Prestasi::normalizeLevel($level);
    }
}
