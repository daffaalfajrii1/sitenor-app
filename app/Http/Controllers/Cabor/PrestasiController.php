<?php

namespace App\Http\Controllers\Cabor;

use App\Http\Controllers\Concerns\ValidatesPrestasiInput;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Cabor\Concerns\ScopesToCabor;
use App\Models\Atlet;
use App\Models\Prestasi;
use Illuminate\Http\Request;

class PrestasiController extends Controller
{
    use ScopesToCabor, ValidatesPrestasiInput;

    public function index(Request $request)
    {
        $caborId = $this->caborId();

        if ($request->filled('atlet_id') && ! $request->has('page')) {
            $atlet = Atlet::query()
                ->where('cabor_id', $caborId)
                ->find($request->atlet_id);
            if ($atlet) {
                return redirect(cabor_route('cabor.prestasi.atlet', ['atlet' => $atlet]));
            }
        }

        $atlets = Atlet::query()
            ->where('cabor_id', $caborId)
            ->withCount('prestasis')
            ->when($request->atlet_id, fn ($q) => $q->where('id', $request->atlet_id))
            ->when($request->search, fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $atletOptions = Atlet::where('cabor_id', $caborId)->orderBy('name')->get();

        return view('cabor.prestasi.index', compact('atlets', 'atletOptions'));
    }

    public function atlet(Atlet $atlet)
    {
        $this->assertModelBelongsToCabor($atlet);

        $atlet->load([
            'cabor',
            'prestasis' => fn ($q) => $q->latest(),
        ]);

        $levelCounts = $atlet->prestasis->groupBy('level')->map->count();

        return view('cabor.prestasi.atlet', [
            'atlet' => $atlet,
            'levelCounts' => $levelCounts,
            'levels' => Prestasi::levelOptions(),
        ]);
    }

    public function create(Request $request)
    {
        $atlets = Atlet::where('cabor_id', $this->caborId())->orderBy('name')->get();

        return view('cabor.prestasi.create', [
            'atlets' => $atlets,
            'selectedAtletId' => $request->atlet_id,
            'levels' => Prestasi::levelOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validatedPrestasi($request);
        $this->assertAtletIdBelongsToCabor($validated['atlet_id']);

        Prestasi::create($validated);

        return redirect(cabor_route('cabor.prestasi.atlet', ['atlet' => $validated['atlet_id']]))
            ->with('success', 'Prestasi berhasil ditambahkan.');
    }

    public function edit(Prestasi $prestasi)
    {
        $this->assertModelBelongsToCabor($prestasi);

        return view('cabor.prestasi.edit', [
            'prestasi' => $prestasi->load('atlet'),
            'atlets' => Atlet::where('cabor_id', $this->caborId())->orderBy('name')->get(),
            'levels' => Prestasi::levelOptions(),
        ]);
    }

    public function update(Request $request, Prestasi $prestasi)
    {
        $this->assertModelBelongsToCabor($prestasi);

        $validated = $this->validatedPrestasi($request);
        $this->assertAtletIdBelongsToCabor($validated['atlet_id']);

        $prestasi->update($validated);

        return redirect(cabor_route('cabor.prestasi.atlet', ['atlet' => $validated['atlet_id']]))
            ->with('success', 'Prestasi berhasil diperbarui.');
    }

    public function destroy(Prestasi $prestasi)
    {
        $this->assertModelBelongsToCabor($prestasi);
        $atletId = $prestasi->atlet_id;
        $prestasi->delete();

        return redirect(cabor_route('cabor.prestasi.atlet', ['atlet' => $atletId]))
            ->with('success', 'Prestasi berhasil dihapus.');
    }
}
