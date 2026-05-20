<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'cabor_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function cabor(): BelongsTo
    {
        return $this->belongsTo(Cabor::class);
    }

    public function avatarUrl(): ?string
    {
        if (! $this->avatar || ! Storage::disk('public')->exists($this->avatar)) {
            return null;
        }

        // Path relatif agar foto tampil di localhost maupun 127.0.0.1:8000
        return '/storage/'.ltrim($this->avatar, '/');
    }
}