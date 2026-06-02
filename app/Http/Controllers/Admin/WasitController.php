<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\ValidatesPersonnelLevel;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Concerns\AppliesListFilters;
use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Models\Cabor;
use App\Models\Wasit;
use Illuminate\Http\Request;

class WasitController extends Controller
{
    use AppliesListFilters, HandlesUploads, ValidatesPersonnelLevel;

    public function index(Request $request)
    {
        $wasits = $this->applyCaborPersonFilters(Wasit::query()->with('cabor'), $request, searchLicense: true)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.wasit.index', [
            'wasits' => $wasits,
            'cabors' => Cabor::orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.wasit.create', ['cabors' => Cabor::orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);
        $validated['slug'] = $this->uniqueSlug($validated['name'], Wasit::class, ['cabor_id' => $validated['cabor_id']]);
        $validated['photo'] = $this->storeUpload($request->file('photo'), 'wasits');
        $validated['is_active'] = $request->boolean('is_active', true);

        Wasit::create($validated);

        return redirect()->route('admin.wasit.index')->with('success', 'Wasit berhasil ditambahkan.');
    }

    public function edit(Wasit $wasit)
    {
        return view('admin.wasit.edit', [
            'wasit' => $wasit,
            'cabors' => Cabor::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Wasit $wasit)
    {
        $validated = $this->validated($request);

        if ($wasit->name !== $validated['name'] || $wasit->cabor_id != $validated['cabor_id']) {
            $validated['slug'] = $this->uniqueSlug($validated['name'], Wasit::class, ['cabor_id' => $validated['cabor_id']], $wasit->id);
        }

        $validated['photo'] = $this->storeUpload($request->file('photo'), 'wasits', $wasit->photo);
        $validated['is_active'] = $request->boolean('is_active');

        $wasit->update($validated);

        return redirect()->route('admin.wasit.index')->with('success', 'Wasit berhasil diperbarui.');
    }

    public function destroy(Wasit $wasit)
    {
        if ($wasit->photo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($wasit->photo);
        }

        $wasit->delete();

        return redirect()->route('admin.wasit.index')->with('success', 'Wasit berhasil dihapus.');
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
