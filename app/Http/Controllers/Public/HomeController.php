<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Public\Concerns\ResolvesPublicStats;
use App\Models\Artikel;

class HomeController extends Controller
{
    use ResolvesPublicStats;

    public function index()
    {
        $stats = $this->stats();

        return view('public.home', [
            'summary' => $stats->summary(),
            'entitiesPerCabor' => $stats->entitiesPerCabor(),
            'pelatihByLevel' => $stats->pelatihByLevel(),
            'wasitJuriByLevel' => $stats->wasitJuriByLevel(),
            'prestasiByLevel' => $stats->prestasiByLevel(),
            'latestArtikels' => Artikel::query()
                ->with('cabor')
                ->where('is_published', true)
                ->latest('published_at')
                ->take(4)
                ->get(),
        ]);
    }
}
