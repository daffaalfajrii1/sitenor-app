<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Pengumuman extends Model
{
    protected $table = 'pengumumans';

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'file_path',
        'file_name',
        'file_size',
        'is_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
            'file_size' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function downloadUrl(): string
    {
        return asset('storage/'.$this->file_path);
    }
}
