<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SiteSetting extends Model
{
    protected $fillable = [
        'app_name',
        'tagline',
        'logo',
        'favicon',
        'visi',
        'misi',
        'email',
        'phone',
        'address',
        'instagram',
        'facebook',
        'youtube',
        'footer_text',
    ];

    public static function current(): self
    {
        return static::query()->firstOrCreate(
            ['id' => 1],
            [
                'app_name' => 'Sitenor Rejang Lebong',
                'tagline' => 'Sistem Informasi Tenaga Olahraga Kabupaten Rejang Lebong',
            ]
        );
    }

    public function logoUrl(): ?string
    {
        return $this->assetUrl($this->logo);
    }

    public function faviconUrl(): ?string
    {
        return $this->assetUrl($this->favicon);
    }

    /** Ikon tab browser: favicon khusus, atau logo website jika favicon kosong. */
    public function tabIconUrl(): ?string
    {
        return $this->faviconUrl() ?? $this->logoUrl();
    }

    public function copyrightLine(): string
    {
        if ($this->footer_text) {
            return $this->footer_text;
        }

        return '© '.date('Y').' '.($this->app_name ?? 'Sitenor');
    }

    public function partnerLine(): string
    {
        return 'Kolaborasi: Diskominfo Rejang Lebong';
    }

    public function socialUrl(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        $value = trim($value);

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        return 'https://'.$value;
    }

    protected function assetUrl(?string $path): ?string
    {
        if (! $path || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        return '/storage/'.ltrim($path, '/');
    }
}
