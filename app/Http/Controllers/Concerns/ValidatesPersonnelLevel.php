<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Prestasi;
use Illuminate\Validation\Rule;

trait ValidatesPersonnelLevel
{
    /**
     * @return array<string, array<int, mixed>>
     */
    protected function levelValidationRules(): array
    {
        return [
            'level' => ['nullable', Rule::in(array_keys(Prestasi::levelOptions()))],
        ];
    }

    /**
     * @param  array<string, mixed>  $validated
     */
    protected function normalizeValidatedLevel(array &$validated): void
    {
        if (array_key_exists('level', $validated)) {
            $validated['level'] = Prestasi::normalizeLevel($validated['level']);
        }
    }
}
