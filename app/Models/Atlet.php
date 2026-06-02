<?php

namespace App\Models;

use App\Models\Concerns\HasStoragePhoto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Atlet extends Model
{
    use HasStoragePhoto;

    protected $fillable = [
        'cabor_id',
        'name',
        'slug',
        'birth_date',
        'gender',
        'phone',
        'email',
        'address',
        'photo',
        'bio',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function cabor(): BelongsTo
    {
        return $this->belongsTo(Cabor::class);
    }

    public function prestasis(): HasMany
    {
        return $this->hasMany(Prestasi::class);
    }

    /** Umur dalam tahun (dari tanggal lahir ke hari ini). */
    public function getAgeAttribute(): ?int
    {
        return $this->birth_date?->age;
    }

    public function getAgeLabelAttribute(): string
    {
        return $this->age !== null ? "{$this->age} tahun" : '—';
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
