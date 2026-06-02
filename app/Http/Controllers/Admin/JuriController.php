<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\ValidatesPersonnelLevel;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Concerns\AppliesListFilters;
use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Models\Cabor;
use App\Models\Juri;
use Illuminate\Http\Request;

class JuriController extends Controller
{
    use AppliesListFilters, HandlesUploads, ValidatesPersonnelLevel;

    public function index(Request $request)
    {
        $juris = $this->applyCaborPersonFilters(Juri::query()->with('cabor'), $request, searchLicense: true)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.juri.index', [
            'juris' => $juris,
            'cabors' => Cabor::orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.juri.create', ['cabors' => Cabor::orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);
        $validated['slug'] = $this->uniqueSlug($validated['name'], Juri::class, ['cabor_id' => $validated['cabor_id']]);
        $validated['photo'] = $this->storeUpload($request->file('photo'), 'juris');
        $validated['is_active'] = $request->boolean('is_active', true);

        Juri::create($validated);

        return redirect()->route('admin.juri.index')->with('success', 'Juri berhasil ditambahkan.');
    }

    public function edit(Juri $juri)
    {
        return view('admin.juri.edit', [
            'juri' => $juri,
            'cabors' => Cabor::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Juri $juri)
    {
        $validated = $this->validated($request);

        if ($juri->name !== $validated['name'] || $juri->cabor_id != $validated['cabor_id']) {
            $validated['slug'] = $this->uniqueSlug($validated['name'], Juri::class, ['cabor_id' => $validated['cabor_id']], $juri->id);
        }

        $validated['photo'] = $this->storeUpload($request->file('photo'), 'juris', $juri->photo);
        $validated['is_active'] = $request->boolean('is_active');

        $juri->update($validated);

        return redirect()->route('admin.juri.index')->with('success', 'Juri berhasil diperbarui.');
    }

    public function destroy(Juri $juri)
    {
        if ($juri->photo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($juri->photo);
        }

        $juri->delete();

        return redirect()->route('admin.juri.index')->with('success', 'Juri berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'cabor_id' => ['required', 'exists:cabors,id'],
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
