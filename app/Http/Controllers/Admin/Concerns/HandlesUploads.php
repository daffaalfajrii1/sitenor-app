<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HandlesUploads
{
    protected function storeUpload(?UploadedFile $file, string $directory, ?string $oldPath = null): ?string
    {
        if (! $file) {
            return $oldPath;
        }

        if ($oldPath) {
            Storage::disk('public')->delete($oldPath);
        }

        return $file->store($directory, 'public');
    }

    protected function uniqueSlug(string $base, string $modelClass, array $scope = [], ?int $ignoreId = null): string
    {
        $slug = Str::slug($base);
        $original = $slug;
        $counter = 1;

        while ($this->slugExists($modelClass, $slug, $scope, $ignoreId)) {
            $slug = $original.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    protected function slugExists(string $modelClass, string $slug, array $scope, ?int $ignoreId): bool
    {
        $query = $modelClass::query()->where('slug', $slug);

        foreach ($scope as $column => $value) {
            $query->where($column, $value);
        }

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->exists();
    }
}
