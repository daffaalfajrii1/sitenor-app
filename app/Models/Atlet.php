<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Atlet extends Model
{
    protected $fillable = [
        'cabor_id',
        'name',
        'slug',
        'nik',
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
}
