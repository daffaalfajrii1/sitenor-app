<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prestasi extends Model
{
    protected $fillable = [
        'atlet_id',
        'title',
        'event_name',
        'level',
        'rank',
        'year',
        'location',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
        ];
    }

    public function atlet(): BelongsTo
    {
        return $this->belongsTo(Atlet::class);
    }

    public static function levelOptions(): array
    {
        return [
            'kabupaten' => 'Kabupaten',
            'provinsi' => 'Provinsi',
            'nasional' => 'Nasional',
            'internasional' => 'Internasional',
        ];
    }

    public static function normalizeLevel(?string $level): ?string
    {
        if ($level === null || $level === '') {
            return null;
        }

        $level = trim($level);
        $options = static::levelOptions();

        if (isset($options[$level])) {
            return $level;
        }

        foreach ($options as $key => $label) {
            if (strcasecmp($level, $label) === 0) {
                return $key;
            }
        }

        return $level;
    }

    /** Peringkat standar kejuaraan */
    public static function rankOptions(): array
    {
        return [
            'Juara 1' => 'Juara 1',
            'Juara 2' => 'Juara 2',
            'Juara 3' => 'Juara 3',
            'Emas' => 'Emas (Medali Emas)',
            'Perak' => 'Perak (Medali Perak)',
            'Perunggu' => 'Perunggu (Medali Perunggu)',
        ];
    }

    public static function isStandardRank(?string $rank): bool
    {
        if ($rank === null || $rank === '') {
            return true;
        }

        return array_key_exists($rank, self::rankOptions());
    }

    public static function resolveRankFromInput(?string $rank, ?string $rankCustom): ?string
    {
        if (in_array($rank, ['__other__', 'lainnya'], true)) {
            $custom = trim((string) $rankCustom);

            return $custom !== '' ? $custom : null;
        }

        return $rank !== null && $rank !== '' ? $rank : null;
    }
}
