<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\Atlet;
use App\Models\Cabor;
use App\Models\Juri;
use App\Models\Pelatih;
use App\Models\Pengumuman;
use App\Models\Prestasi;
use App\Models\User;
use App\Models\Wasit;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $currentYear = (int) now()->year;

        return view('admin.dashboard', [
            'stats' => [
                'cabor' => Cabor::count(),
                'atlet' => Atlet::count(),
                'pelatih' => Pelatih::count(),
                'wasit' => Wasit::count(),
                'juri' => Juri::count(),
                'kepala_cabor' => User::role(User::ROLE_ADMIN_CABOR)->count(),
                'prestasi' => Prestasi::count(),
                'artikel_terbit' => Artikel::where('is_published', true)->count(),
                'artikel_draft' => Artikel::where('is_published', false)->count(),
                'pengumuman' => Pengumuman::count(),
                'prestasi_tahun_ini' => Prestasi::where('year', $currentYear)->count(),
            ],
            'prestasiByYear' => Prestasi::query()
                ->select('year', DB::raw('COUNT(*) as total'))
                ->whereNotNull('year')
                ->groupBy('year')
                ->orderByDesc('year')
                ->limit(8)
                ->get(),
            'prestasiByCaborYear' => $this->prestasiByCaborYear(),
            'caborSummary' => Cabor::query()
                ->withCount(['atlets', 'pelatihs', 'wasits', 'juris', 'prestasis'])
                ->orderBy('name')
                ->get(),
            'recentPublishedArtikels' => Artikel::query()
                ->with(['cabor', 'user'])
                ->where('is_published', true)
                ->latest('published_at')
                ->take(6)
                ->get(),
            'recentPrestasis' => Prestasi::query()
                ->with(['atlet.cabor'])
                ->latest()
                ->take(6)
                ->get(),
            'recentCabors' => Cabor::latest()->take(5)->get(),
            'currentYear' => $currentYear,
        ]);
    }

    /**
     * @return Collection<int, object{cabor_id: int, cabor_name: string, year: int, total: int}>
     */
    private function prestasiByCaborYear(): Collection
    {
        return Prestasi::query()
            ->join('atlets', 'prestasis.atlet_id', '=', 'atlets.id')
            ->join('cabors', 'atlets.cabor_id', '=', 'cabors.id')
            ->select([
                'cabors.id as cabor_id',
                'cabors.name as cabor_name',
                'prestasis.year',
                DB::raw('COUNT(*) as total'),
            ])
            ->whereNotNull('prestasis.year')
            ->groupBy('cabors.id', 'cabors.name', 'prestasis.year')
            ->orderByDesc('prestasis.year')
            ->orderBy('cabors.name')
            ->get();
    }
}
