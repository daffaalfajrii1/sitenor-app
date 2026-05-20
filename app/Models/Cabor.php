<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Cabor extends Model
{
    protected $fillable = [
        'kode',
        'name',
        'slug',
        'description',
        'logo',
        'icon',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public static function generateKode(): string
    {
        $prefix = 'CAB';
        $year = now()->format('Y');

        $last = static::query()
            ->where('kode', 'like', "{$prefix}-{$year}-%")
            ->orderByDesc('kode')
            ->value('kode');

        $sequence = 1;

        if ($last && preg_match('/-(\d+)$/', $last, $matches)) {
            $sequence = (int) $matches[1] + 1;
        }

        return sprintf('%s-%s-%03d', $prefix, $year, $sequence);
    }

    public function atlets(): HasMany
    {
        return $this->hasMany(Atlet::class);
    }

    public function prestasis(): HasManyThrough
    {
        return $this->hasManyThrough(Prestasi::class, Atlet::class);
    }

    public function pelatihs(): HasMany
    {
        return $this->hasMany(Pelatih::class);
    }

    public function wasits(): HasMany
    {
        return $this->hasMany(Wasit::class);
    }

    public function juris(): HasMany
    {
        return $this->hasMany(Juri::class);
    }

    public function artikels(): HasMany
    {
        return $this->hasMany(Artikel::class);
    }
}
