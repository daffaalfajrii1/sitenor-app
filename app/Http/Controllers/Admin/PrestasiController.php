<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\ValidatesPrestasiInput;
use App\Http\Controllers\Controller;
use App\Models\Atlet;
use App\Models\Cabor;
use App\Models\Prestasi;
use Illuminate\Http\Request;

class PrestasiController extends Controller
{
    use ValidatesPrestasiInput;

    public function index(Request $request)
    {
        if ($request->filled('atlet_id') && ! $request->has('page')) {
            $atlet = Atlet::find($request->atlet_id);
            if ($atlet) {
                return redirect()->route('admin.prestasi.atlet', $atlet);
            }
        }

        $atlets = Atlet::query()
            ->with('cabor')
            ->withCount('prestasis')
            ->when($request->cabor_id, fn ($q) => $q->where('cabor_id', $request->cabor_id))
            ->when($request->atlet_id, fn ($q) => $q->where('id', $request->atlet_id))
            ->when($request->search, fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.prestasi.index', [
            'atlets' => $atlets,
            'cabors' => Cabor::orderBy('name')->get(),
            'atletOptions' => Atlet::with('cabor')->orderBy('name')->get(),
        ]);
    }

    public function atlet(Atlet $atlet)
    {
        $atlet->load([
            'cabor',
            'prestasis' => fn ($q) => $q->latest(),
        ]);

        $levelCounts = $atlet->prestasis
            ->groupBy('level')
            ->map->count();

        return view('admin.prestasi.atlet', [
            'atlet' => $atlet,
            'levelCounts' => $levelCounts,
            'levels' => Prestasi::levelOptions(),
        ]);
    }

    public function create(Request $request)
    {
        return view('admin.prestasi.create', [
            'atlets' => Atlet::with('cabor')->orderBy('name')->get(),
            'selectedAtletId' => $request->atlet_id,
            'levels' => Prestasi::levelOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validatedPrestasi($request);

        Prestasi::create($validated);

        return redirect()
            ->route('admin.prestasi.atlet', $validated['atlet_id'])
            ->with('success', 'Prestasi berhasil ditambahkan.');
    }

    public function edit(Prestasi $prestasi)
    {
        return view('admin.prestasi.edit', [
            'prestasi' => $prestasi->load('atlet.cabor'),
            'atlets' => Atlet::with('cabor')->orderBy('name')->get(),
            'levels' => Prestasi::levelOptions(),
        ]);
    }

    public function update(Request $request, Prestasi $prestasi)
    {
        $validated = $this->validatedPrestasi($request);

        $prestasi->update($validated);

        return redirect()
            ->route('admin.prestasi.atlet', $validated['atlet_id'])
            ->with('success', 'Prestasi berhasil diperbarui.');
    }

    public function destroy(Prestasi $prestasi)
    {
        $atletId = $prestasi->atlet_id;
        $prestasi->delete();

        return redirect()
            ->route('admin.prestasi.atlet', $atletId)
            ->with('success', 'Prestasi berhasil dihapus.');
    }
}
