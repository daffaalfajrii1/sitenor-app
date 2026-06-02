<?php

namespace App\Services\Public;

use App\Models\Artikel;
use App\Models\Atlet;
use App\Models\Cabor;
use App\Models\Juri;
use App\Models\Pelatih;
use App\Models\Prestasi;
use App\Models\Wasit;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PublicStatsService
{
    public function summary(): array
    {
        return [
            'cabor' => Cabor::where('is_active', true)->count(),
            'atlet' => Atlet::where('is_active', true)->count(),
            'pelatih' => Pelatih::where('is_active', true)->count(),
            'wasit' => Wasit::where('is_active', true)->count(),
            'juri' => Juri::where('is_active', true)->count(),
            'wasit_juri' => Wasit::where('is_active', true)->count() + Juri::where('is_active', true)->count(),
            'prestasi' => Prestasi::query()
                ->whereHas('atlet', fn ($q) => $q->where('is_active', true))
                ->count(),
            'prestasi_nasional' => $this->prestasiCountByLevels(['nasional']),
            'prestasi_internasional' => $this->prestasiCountByLevels(['internasional']),
            'artikel' => Artikel::where('is_published', true)->count(),
        ];
    }

    /**
     * @return Collection<int, object{name: string, atlet: int, pelatih: int, wasit_juri: int, total: int}>
     */
    public function entitiesPerCabor(): Collection
    {
        return Cabor::query()
            ->where('is_active', true)
            ->withCount([
                'atlets as atlet_count' => fn ($q) => $q->where('is_active', true),
                'pelatihs as pelatih_count' => fn ($q) => $q->where('is_active', true),
                'wasits as wasit_count' => fn ($q) => $q->where('is_active', true),
                'juris as juri_count' => fn ($q) => $q->where('is_active', true),
            ])
            ->orderBy('name')
            ->get()
            ->map(fn (Cabor $c) => (object) [
                'name' => $c->name,
                'atlet' => (int) $c->atlet_count,
                'pelatih' => (int) $c->pelatih_count,
                'wasit_juri' => (int) $c->wasit_count + (int) $c->juri_count,
                'total' => (int) $c->atlet_count + (int) $c->pelatih_count + (int) $c->wasit_count + (int) $c->juri_count,
            ]);
    }

    /**
     * @return array<string, int>
     */
    public function pelatihByLevel(): array
    {
        return $this->countByLevel(Pelatih::query()->where('is_active', true));
    }

    /**
     * @return array<string, int>
     */
    public function wasitJuriByLevel(): array
    {
        $wasit = $this->countByLevel(Wasit::query()->where('is_active', true));
        $juri = $this->countByLevel(Juri::query()->where('is_active', true));

        $keys = array_unique(array_merge(array_keys($wasit), array_keys($juri)));

        $merged = [];
        foreach ($keys as $key) {
            $merged[$key] = ($wasit[$key] ?? 0) + ($juri[$key] ?? 0);
        }

        return $merged;
    }

    /**
     * Prestasi atlet dikelompokkan per level kejuaraan.
     *
     * @return array<string, int>
     */
    public function prestasiByLevel(): array
    {
        $rows = Prestasi::query()
            ->whereHas('atlet', fn ($q) => $q->where('is_active', true))
            ->select('level', DB::raw('COUNT(*) as total'))
            ->groupBy('level')
            ->pluck('total', 'level');

        $out = [];
        foreach (Prestasi::levelOptions() as $key => $label) {
            $out[$label] = (int) ($rows[$key] ?? 0);
        }

        return $out;
    }

    /**
     * @return Collection<int, object{cabor_name: string, nasional: int, internasional: int, provinsi: int, kabupaten: int, total: int}>
     */
    public function prestasiPerCaborByLevel(): Collection
    {
        $rows = Prestasi::query()
            ->join('atlets', 'prestasis.atlet_id', '=', 'atlets.id')
            ->join('cabors', 'atlets.cabor_id', '=', 'cabors.id')
            ->where('atlets.is_active', true)
            ->where('cabors.is_active', true)
            ->select([
                'cabors.name as cabor_name',
                'prestasis.level',
                DB::raw('COUNT(*) as total'),
            ])
            ->groupBy('cabors.id', 'cabors.name', 'prestasis.level')
            ->get();

        return $rows->groupBy('cabor_name')->map(function ($group, $caborName) {
            $byLevel = $group->pluck('total', 'level');

            return (object) [
                'cabor_name' => $caborName,
                'kabupaten' => (int) ($byLevel['kabupaten'] ?? 0),
                'provinsi' => (int) ($byLevel['provinsi'] ?? 0),
                'nasional' => (int) ($byLevel['nasional'] ?? 0),
                'internasional' => (int) ($byLevel['internasional'] ?? 0),
                'total' => (int) $group->sum('total'),
            ];
        })->sortByDesc('total')->values();
    }

    /**
     * @return Collection<int, object{year: int, total: int}>
     */
    public function prestasiByYear(): Collection
    {
        return Prestasi::query()
            ->whereHas('atlet', fn ($q) => $q->where('is_active', true))
            ->whereNotNull('year')
            ->select('year', DB::raw('COUNT(*) as total'))
            ->groupBy('year')
            ->orderByDesc('year')
            ->limit(10)
            ->get();
    }

    private function prestasiCountByLevels(array $levels): int
    {
        return Prestasi::query()
            ->whereHas('atlet', fn ($q) => $q->where('is_active', true))
            ->whereIn('level', $levels)
            ->count();
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return array<string, int>
     */
    private function countByLevel($query): array
    {
        $rows = (clone $query)
            ->select('level', DB::raw('COUNT(*) as total'))
            ->groupBy('level')
            ->pluck('total', 'level');

        $out = ['Lainnya' => 0];
        foreach (Prestasi::levelOptions() as $key => $label) {
            $out[$label] = (int) ($rows[$key] ?? 0);
        }

        $known = array_keys(Prestasi::levelOptions());
        foreach ($rows as $level => $total) {
            if ($level && ! in_array($level, $known, true)) {
                $out['Lainnya'] += (int) $total;
            }
        }

        if ($out['Lainnya'] === 0) {
            unset($out['Lainnya']);
        }

        return array_filter($out, fn ($v) => $v > 0);
    }
}
