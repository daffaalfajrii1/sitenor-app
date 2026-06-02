<?php

namespace App\Models;

use App\Models\Concerns\HasCompetitionLevel;
use App\Models\Concerns\HasStoragePhoto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pelatih extends Model
{
    use HasCompetitionLevel;
    use HasStoragePhoto;

    protected $fillable = [
        'cabor_id',
        'name',
        'slug',
        'license_number',
        'level',
        'phone',
        'email',
        'photo',
        'bio',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function cabor(): BelongsTo
    {
        return $this->belongsTo(Cabor::class);
    }
}
