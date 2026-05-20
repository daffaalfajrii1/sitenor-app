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
}
