<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Atlet;
use App\Models\Cabor;
use App\Models\Prestasi;
use Illuminate\Http\Request;

class PrestasiController extends Controller
{
    public function index(Request $request)
    {
        $prestasis = Prestasi::query()
            ->with(['atlet.cabor'])
            ->when($request->atlet_id, fn ($q) => $q->where('atlet_id', $request->atlet_id))
            ->when($request->cabor_id, fn ($q) => $q->whereHas('atlet', fn ($a) => $a->where('cabor_id', $request->cabor_id)))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.prestasi.index', [
            'prestasis' => $prestasis,
            'cabors' => Cabor::orderBy('name')->get(),
            'atlets' => Atlet::with('cabor')->orderBy('name')->get(),
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
        $validated = $request->validate([
            'atlet_id' => ['required', 'exists:atlets,id'],
            'title' => ['required', 'string', 'max:255'],
            'event_name' => ['nullable', 'string', 'max:255'],
            'level' => ['required', 'in:kabupaten,provinsi,nasional,internasional'],
            'rank' => ['nullable', 'string', 'max:100'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        Prestasi::create($validated);

        return redirect()->route('admin.prestasi.index')->with('success', 'Prestasi berhasil ditambahkan.');
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
        $validated = $request->validate([
            'atlet_id' => ['required', 'exists:atlets,id'],
            'title' => ['required', 'string', 'max:255'],
            'event_name' => ['nullable', 'string', 'max:255'],
            'level' => ['required', 'in:kabupaten,provinsi,nasional,internasional'],
            'rank' => ['nullable', 'string', 'max:100'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $prestasi->update($validated);

        return redirect()->route('admin.prestasi.index')->with('success', 'Prestasi berhasil diperbarui.');
    }

    public function destroy(Prestasi $prestasi)
    {
        $prestasi->delete();

        return redirect()->route('admin.prestasi.index')->with('success', 'Prestasi berhasil dihapus.');
    }
}
