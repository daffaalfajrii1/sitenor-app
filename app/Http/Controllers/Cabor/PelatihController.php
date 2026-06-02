<?php

namespace App\Http\Controllers\Cabor;

use App\Http\Controllers\Concerns\ValidatesPersonnelLevel;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Concerns\AppliesListFilters;
use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Http\Controllers\Cabor\Concerns\ScopesToCabor;
use App\Models\Pelatih;
use Illuminate\Http\Request;

class PelatihController extends Controller
{
    use AppliesListFilters, HandlesUploads, ScopesToCabor, ValidatesPersonnelLevel;

    public function index(Request $request)
    {
        $pelatihs = $this->applyCaborPersonFilters(
            Pelatih::query()->where('cabor_id', $this->caborId()),
            $request,
            searchLicense: true
        )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('cabor.pelatih.index', compact('pelatihs'));
    }

    public function create()
    {
        return view('cabor.pelatih.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);
        $caborId = $this->caborId();
        $validated['cabor_id'] = $caborId;
        $validated['slug'] = $this->uniqueSlug($validated['name'], Pelatih::class, ['cabor_id' => $caborId]);
        $validated['photo'] = $this->storeUpload($request->file('photo'), 'pelatihs');
        $validated['is_active'] = $request->boolean('is_active', true);

        Pelatih::create($validated);

        return redirect(cabor_route('cabor.pelatih.index'))->with('success', 'Pelatih berhasil ditambahkan.');
    }

    public function edit(Pelatih $pelatih)
    {
        $this->assertModelBelongsToCabor($pelatih);

        return view('cabor.pelatih.edit', compact('pelatih'));
    }

    public function update(Request $request, Pelatih $pelatih)
    {
        $this->assertModelBelongsToCabor($pelatih);

        $validated = $this->validated($request);
        $caborId = $this->caborId();
        $validated['cabor_id'] = $caborId;

        if ($pelatih->name !== $validated['name']) {
            $validated['slug'] = $this->uniqueSlug($validated['name'], Pelatih::class, ['cabor_id' => $caborId], $pelatih->id);
        }

        $validated['photo'] = $this->storeUpload($request->file('photo'), 'pelatihs', $pelatih->photo);
        $validated['is_active'] = $request->boolean('is_active');

        $pelatih->update($validated);

        return redirect(cabor_route('cabor.pelatih.index'))->with('success', 'Pelatih berhasil diperbarui.');
    }

    public function destroy(Pelatih $pelatih)
    {
        $this->assertModelBelongsToCabor($pelatih);

        if ($pelatih->photo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($pelatih->photo);
        }

        $pelatih->delete();

        return redirect(cabor_route('cabor.pelatih.index'))->with('success', 'Pelatih berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        $validated = $request->validate([
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
