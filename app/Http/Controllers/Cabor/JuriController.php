<?php

namespace App\Http\Controllers\Cabor;

use App\Http\Controllers\Concerns\ValidatesPersonnelLevel;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Concerns\AppliesListFilters;
use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Http\Controllers\Cabor\Concerns\ScopesToCabor;
use App\Models\Juri;
use Illuminate\Http\Request;

class JuriController extends Controller
{
    use AppliesListFilters, HandlesUploads, ScopesToCabor, ValidatesPersonnelLevel;

    public function index(Request $request)
    {
        $juris = $this->applyCaborPersonFilters(
            Juri::query()->where('cabor_id', $this->caborId()),
            $request,
            searchLicense: true
        )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('cabor.juri.index', compact('juris'));
    }

    public function create()
    {
        return view('cabor.juri.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);
        $caborId = $this->caborId();
        $validated['cabor_id'] = $caborId;
        $validated['slug'] = $this->uniqueSlug($validated['name'], Juri::class, ['cabor_id' => $caborId]);
        $validated['photo'] = $this->storeUpload($request->file('photo'), 'juris');
        $validated['is_active'] = $request->boolean('is_active', true);

        Juri::create($validated);

        return redirect(cabor_route('cabor.juri.index'))->with('success', 'Juri berhasil ditambahkan.');
    }

    public function edit(Juri $juri)
    {
        $this->assertModelBelongsToCabor($juri);

        return view('cabor.juri.edit', compact('juri'));
    }

    public function update(Request $request, Juri $juri)
    {
        $this->assertModelBelongsToCabor($juri);

        $validated = $this->validated($request);
        $caborId = $this->caborId();
        $validated['cabor_id'] = $caborId;

        if ($juri->name !== $validated['name']) {
            $validated['slug'] = $this->uniqueSlug($validated['name'], Juri::class, ['cabor_id' => $caborId], $juri->id);
        }

        $validated['photo'] = $this->storeUpload($request->file('photo'), 'juris', $juri->photo);
        $validated['is_active'] = $request->boolean('is_active');

        $juri->update($validated);

        return redirect(cabor_route('cabor.juri.index'))->with('success', 'Juri berhasil diperbarui.');
    }

    public function destroy(Juri $juri)
    {
        $this->assertModelBelongsToCabor($juri);

        if ($juri->photo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($juri->photo);
        }

        $juri->delete();

        return redirect(cabor_route('cabor.juri.index'))->with('success', 'Juri berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'license_number' => ['nullable', 'string', 'max:100'],
            ...$this->levelValidationRules(),
            'phone' => ['nullable', 'string', 'max:20'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $this->normalizeValidatedLevel($validated);

        return $validated;
    }
}
