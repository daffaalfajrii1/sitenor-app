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
    /**
     * @return null Daftar semua cabor aktif (tanpa filter).
     * @return false Tidak ada cabor yang cocok dengan pencarian.
     * @return list<int> ID cabor yang cocok.
     */
    public function resolveCaborIds(?string $search): null|false|array
    {
        $search = trim((string) $search);

        if ($search === '') {
            return null;
        }

        $ids = Cabor::query()
            ->where('is_active', true)
            ->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('kode', 'like', "%{$search}%");
            })
            ->pluck('id')
            ->all();

        return $ids === [] ? false : $ids;
    }

    public function summary(?string $search = null): array
    {
        $caborIds = $this->resolveCaborIds($search);

        if ($caborIds === false) {
            return $this->emptySummary();
        }

        $atletQuery = Atlet::query()->where('is_active', true);
        $pelatihQuery = Pelatih::query()->where('is_active', true);
        $wasitQuery = Wasit::query()->where('is_active', true);
        $juriQuery = Juri::query()->where('is_active', true);
        $prestasiQuery = Prestasi::query()
            ->whereHas('atlet', fn ($q) => $q->where('is_active', true));

        if (is_array($caborIds)) {
            $atletQuery->whereIn('cabor_id', $caborIds);
            $pelatihQuery->whereIn('cabor_id', $caborIds);
            $wasitQuery->whereIn('cabor_id', $caborIds);
            $juriQuery->whereIn('cabor_id', $caborIds);
            $prestasiQuery->whereHas('atlet', fn ($q) => $q->whereIn('cabor_id', $caborIds));
        }

        $wasitCount = (clone $wasitQuery)->count();
        $juriCount = (clone $juriQuery)->count();

        return [
            'cabor' => is_array($caborIds)
                ? count($caborIds)
                : Cabor::where('is_active', true)->count(),
            'atlet' => $atletQuery->count(),
            'pelatih' => $pelatihQuery->count(),
            'wasit' => $wasitCount,
            'juri' => $juriCount,
            'wasit_juri' => $wasitCount + $juriCount,
            'prestasi' => $prestasiQuery->count(),
            'prestasi_nasional' => $this->prestasiCountByLevels(['nasional'], $caborIds),
            'prestasi_internasional' => $this->prestasiCountByLevels(['internasional'], $caborIds),
            'artikel' => Artikel::where('is_published', true)->count(),
        ];
    }

    /**
     * @return array<string, int>
     */
    public function emptySummary(): array
    {
        return [
            'cabor' => 0,
            'atlet' => 0,
            'pelatih' => 0,
            'wasit' => 0,
            'juri' => 0,
            'wasit_juri' => 0,
            'prestasi' => 0,
            'prestasi_nasional' => 0,
            'prestasi_internasional' => 0,
            'artikel' => Artikel::where('is_published', true)->count(),
        ];
    }

    /**
     * @return Collection<int, object{name: string, atlet: int, pelatih: int, wasit_juri: int, total: int}>
     */
    public function entitiesPerCabor(?string $search = null): Collection
    {
        $caborIds = $this->resolveCaborIds($search);

        if ($caborIds === false) {
            return collect();
        }

        return Cabor::query()
            ->where('is_active', true)
            ->when(is_array($caborIds), fn ($q) => $q->whereIn('id', $caborIds))
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
    public function pelatihByLevel(?string $search = null): array
    {
        $query = Pelatih::query()->where('is_active', true);
        $this->applyCaborScope($query, $search);

        return $this->countByLevel($query);
    }

    /**
     * @return array<string, int>
     */
    public function wasitJuriByLevel(?string $search = null): array
    {
        $wasitQuery = Wasit::query()->where('is_active', true);
        $juriQuery = Juri::query()->where('is_active', true);
        $this->applyCaborScope($wasitQuery, $search);
        $this->applyCaborScope($juriQuery, $search);

        $wasit = $this->countByLevel($wasitQuery);
        $juri = $this->countByLevel($juriQuery);

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
    public function prestasiByLevel(?string $search = null): array
    {
        $caborIds = $this->resolveCaborIds($search);

        if ($caborIds === false) {
            return [];
        }

        $rows = Prestasi::query()
            ->whereHas('atlet', function ($q) use ($caborIds) {
                $q->where('is_active', true);
                if (is_array($caborIds)) {
                    $q->whereIn('cabor_id', $caborIds);
                }
            })
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
    public function prestasiPerCaborByLevel(?string $search = null): Collection
    {
        $caborIds = $this->resolveCaborIds($search);

        if ($caborIds === false) {
            return collect();
        }

        $rows = Prestasi::query()
            ->join('atlets', 'prestasis.atlet_id', '=', 'atlets.id')
            ->join('cabors', 'atlets.cabor_id', '=', 'cabors.id')
            ->where('atlets.is_active', true)
            ->where('cabors.is_active', true)
            ->when(is_array($caborIds), fn ($q) => $q->whereIn('cabors.id', $caborIds))
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
    public function prestasiByYear(?string $search = null): Collection
    {
        $caborIds = $this->resolveCaborIds($search);

        if ($caborIds === false) {
            return collect();
        }

        return Prestasi::query()
            ->whereHas('atlet', function ($q) use ($caborIds) {
                $q->where('is_active', true);
                if (is_array($caborIds)) {
                    $q->whereIn('cabor_id', $caborIds);
                }
            })
            ->whereNotNull('year')
            ->select('year', DB::raw('COUNT(*) as total'))
            ->groupBy('year')
            ->orderByDesc('year')
            ->limit(10)
            ->get();
    }

    /**
     * @param  null|false|list<int>  $caborIds
     */
    private function prestasiCountByLevels(array $levels, null|false|array $caborIds = null): int
    {
        if ($caborIds === false) {
            return 0;
        }

        return Prestasi::query()
            ->whereHas('atlet', function ($q) use ($caborIds) {
                $q->where('is_active', true);
                if (is_array($caborIds)) {
                    $q->whereIn('cabor_id', $caborIds);
                }
            })
            ->whereIn('level', $levels)
            ->count();
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    private function applyCaborScope($query, ?string $search): void
    {
        $caborIds = $this->resolveCaborIds($search);

        if (is_array($caborIds)) {
            $query->whereIn('cabor_id', $caborIds);
        }
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
