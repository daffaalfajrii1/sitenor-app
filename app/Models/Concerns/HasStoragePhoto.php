<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Storage;

trait HasStoragePhoto
{
    public function photoUrl(): ?string
    {
        if (! $this->photo || ! Storage::disk('public')->exists($this->photo)) {
            return null;
        }

        return asset('storage/'.ltrim($this->photo, '/'));
    }

    public function photoInitial(): string
    {
        return mb_strtoupper(mb_substr($this->name ?? '?', 0, 1));
    }
}
