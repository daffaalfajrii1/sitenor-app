<?php

namespace App\Http\Controllers\Cabor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Cabor\Concerns\ScopesToCabor;
use App\Models\Artikel;
use App\Models\Atlet;
use App\Models\Juri;
use App\Models\Pelatih;
use App\Models\Prestasi;
use App\Models\Wasit;

class DashboardController extends Controller
{
    use ScopesToCabor;

    public function index()
    {
        $caborId = $this->caborId();
        $cabor = auth()->user()->cabor;

        return view('cabor.dashboard', [
            'cabor' => $cabor,
            'stats' => [
                'atlet' => Atlet::where('cabor_id', $caborId)->count(),
                'pelatih' => Pelatih::where('cabor_id', $caborId)->count(),
                'wasit' => Wasit::where('cabor_id', $caborId)->count(),
                'juri' => Juri::where('cabor_id', $caborId)->count(),
                'prestasi' => Prestasi::whereHas('atlet', fn ($q) => $q->where('cabor_id', $caborId))->count(),
                'artikel' => Artikel::where('cabor_id', $caborId)->count(),
            ],
        ]);
    }
}
