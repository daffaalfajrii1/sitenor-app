<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Public\Concerns\ResolvesPublicStats;
use App\Models\Cabor;
use Illuminate\Http\Request;

class StatistikController extends Controller
{
    use ResolvesPublicStats;

    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $stats = $this->stats();
        $caborIds = $stats->resolveCaborIds($search);

        $caborQuery = Cabor::query()
            ->where('is_active', true)
            ->when($caborIds === false, fn ($q) => $q->whereRaw('0 = 1'))
            ->when(is_array($caborIds), fn ($q) => $q->whereIn('id', $caborIds))
            ->orderBy('name');

        return view('public.statistik', [
            'search' => $search,
            'summary' => $stats->summary($search),
            'entitiesPerCabor' => $stats->entitiesPerCabor($search),
            'pelatihByLevel' => $stats->pelatihByLevel($search),
            'wasitJuriByLevel' => $stats->wasitJuriByLevel($search),
            'prestasiByLevel' => $stats->prestasiByLevel($search),
            'prestasiPerCabor' => $stats->prestasiPerCaborByLevel($search),
            'prestasiByYear' => $stats->prestasiByYear($search),
            'caborSummary' => $caborQuery
                ->withCount([
                    'atlets as atlet_count' => fn ($q) => $q->where('is_active', true),
                    'pelatihs as pelatih_count' => fn ($q) => $q->where('is_active', true),
                    'wasits as wasit_count' => fn ($q) => $q->where('is_active', true),
                    'juris as juri_count' => fn ($q) => $q->where('is_active', true),
                    'prestasis',
                ])
                ->get(),
        ]);
    }
}
