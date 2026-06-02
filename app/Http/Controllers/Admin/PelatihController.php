<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\ValidatesPersonnelLevel;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Concerns\AppliesListFilters;
use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Models\Cabor;
use App\Models\Pelatih;
use Illuminate\Http\Request;

class PelatihController extends Controller
{
    use AppliesListFilters, HandlesUploads, ValidatesPersonnelLevel;

    public function index(Request $request)
    {
        $pelatihs = $this->applyCaborPersonFilters(Pelatih::query()->with('cabor'), $request, searchLicense: true)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.pelatih.index', [
            'pelatihs' => $pelatihs,
            'cabors' => Cabor::orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.pelatih.create', ['cabors' => Cabor::orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);
        $validated['slug'] = $this->uniqueSlug($validated['name'], Pelatih::class, ['cabor_id' => $validated['cabor_id']]);
        $validated['photo'] = $this->storeUpload($request->file('photo'), 'pelatihs');
        $validated['is_active'] = $request->boolean('is_active', true);

        Pelatih::create($validated);

        return redirect()->route('admin.pelatih.index')->with('success', 'Pelatih berhasil ditambahkan.');
    }

    public function edit(Pelatih $pelatih)
    {
        return view('admin.pelatih.edit', [
            'pelatih' => $pelatih,
            'cabors' => Cabor::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Pelatih $pelatih)
    {
        $validated = $this->validated($request);

        if ($pelatih->name !== $validated['name'] || $pelatih->cabor_id != $validated['cabor_id']) {
            $validated['slug'] = $this->uniqueSlug($validated['name'], Pelatih::class, ['cabor_id' => $validated['cabor_id']], $pelatih->id);
        }

        $validated['photo'] = $this->storeUpload($request->file('photo'), 'pelatihs', $pelatih->photo);
        $validated['is_active'] = $request->boolean('is_active');

        $pelatih->update($validated);

        return redirect()->route('admin.pelatih.index')->with('success', 'Pelatih berhasil diperbarui.');
    }

    public function destroy(Pelatih $pelatih)
    {
        if ($pelatih->photo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($pelatih->photo);
        }

        $pelatih->delete();

        return redirect()->route('admin.pelatih.index')->with('success', 'Pelatih berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'cabor_id' => ['required', 'exists:cabors,id'],
            'name' => ['required', 'string', 'max:255'],
            'license_number' => ['nullable', 'string', 'max:100'],
            ...$this->levelValidationRules(),
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'bio' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $this->normalizeValidatedLevel($validated);

        return $validated;
    }
}
