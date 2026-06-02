<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Prestasi;
use Illuminate\Http\Request;

trait ValidatesPrestasiInput
{
    protected function validatedPrestasi(Request $request): array
    {
        $validated = $request->validate([
            'atlet_id' => ['required', 'exists:atlets,id'],
            'title' => ['required', 'string', 'max:255'],
            'event_name' => ['nullable', 'string', 'max:255'],
            'level' => ['required', 'in:kabupaten,provinsi,nasional,internasional'],
            'rank' => ['nullable', 'string', 'max:100'],
            'rank_custom' => ['nullable', 'string', 'max:100'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $validated['rank'] = Prestasi::resolveRankFromInput(
            $request->input('rank'),
            $request->input('rank_custom')
        );

        unset($validated['rank_custom']);

        return $validated;
    }
}
