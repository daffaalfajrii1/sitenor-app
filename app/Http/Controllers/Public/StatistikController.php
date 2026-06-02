<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Public\Concerns\ResolvesPublicStats;
use App\Models\Cabor;

class StatistikController extends Controller
{
    use ResolvesPublicStats;

    public function index()
    {
        $stats = $this->stats();

        return view('public.statistik', [
            'summary' => $stats->summary(),
            'entitiesPerCabor' => $stats->entitiesPerCabor(),
            'pelatihByLevel' => $stats->pelatihByLevel(),
            'wasitJuriByLevel' => $stats->wasitJuriByLevel(),
            'prestasiByLevel' => $stats->prestasiByLevel(),
            'prestasiPerCabor' => $stats->prestasiPerCaborByLevel(),
            'prestasiByYear' => $stats->prestasiByYear(),
            'caborSummary' => Cabor::query()
                ->where('is_active', true)
                ->withCount([
                    'atlets as atlet_count' => fn ($q) => $q->where('is_active', true),
                    'pelatihs as pelatih_count' => fn ($q) => $q->where('is_active', true),
                    'wasits as wasit_count' => fn ($q) => $q->where('is_active', true),
                    'juris as juri_count' => fn ($q) => $q->where('is_active', true),
                    'prestasis',
                ])
                ->orderBy('name')
                ->get(),
        ]);
    }
}
