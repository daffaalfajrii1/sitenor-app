<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Juri extends Model
{
    protected $table = 'juris';

    protected $fillable = [
        'cabor_id',
        'name',
        'slug',
        'license_number',
        'level',
        'phone',
        'photo',
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
