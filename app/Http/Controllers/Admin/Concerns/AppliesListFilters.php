<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait AppliesListFilters
{
    protected function applyCaborPersonFilters(Builder $query, Request $request, bool $withGender = false, bool $searchLicense = false): Builder
    {
        return $query
            ->when($request->cabor_id, fn ($q) => $q->where('cabor_id', $request->cabor_id))
            ->when($request->search, function ($q, $s) use ($searchLicense) {
                $q->where(function ($q) use ($s, $searchLicense) {
                    $q->where('name', 'like', "%{$s}%");
                    if ($searchLicense) {
                        $q->orWhere('license_number', 'like', "%{$s}%")
                            ->orWhere('level', 'like', "%{$s}%");
                    }
                });
            })
            ->when($request->filled('is_active'), fn ($q) => $q->where('is_active', $request->boolean('is_active')))
            ->when($withGender && $request->gender, fn ($q, $g) => $q->where('gender', $g));
    }
}
