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

    public const ROLE_ADMIN_CABOR = 'admin cabor';

    public const ROLE_SUPER_ADMIN = 'super admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'cabor_id',
        'phone',
        'instagram',
        'facebook',
        'youtube',
        'tiktok',
        'bio',
        'profile_completed_at',
        'registered_by_admin',
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
            'profile_completed_at' => 'datetime',
            'registered_by_admin' => 'boolean',
        ];
    }

    public function cabor(): BelongsTo
    {
        return $this->belongsTo(Cabor::class);
    }

    public function needsProfileCompletion(): bool
    {
        return $this->hasRole(self::ROLE_ADMIN_CABOR)
            && ! $this->registered_by_admin
            && is_null($this->profile_completed_at);
    }

    public function isAdminCabor(): bool
    {
        return $this->hasRole(self::ROLE_ADMIN_CABOR);
    }

    public function markProfileCompleted(): void
    {
        $this->forceFill(['profile_completed_at' => now()])->save();
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