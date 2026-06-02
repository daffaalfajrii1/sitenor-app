<?php

namespace App\Http\Controllers\Cabor;

use App\Http\Controllers\Concerns\ValidatesPersonnelLevel;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Concerns\AppliesListFilters;
use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Http\Controllers\Cabor\Concerns\ScopesToCabor;
use App\Models\Wasit;
use Illuminate\Http\Request;

class WasitController extends Controller
{
    use AppliesListFilters, HandlesUploads, ScopesToCabor, ValidatesPersonnelLevel;

    public function index(Request $request)
    {
        $wasits = $this->applyCaborPersonFilters(
            Wasit::query()->where('cabor_id', $this->caborId()),
            $request,
            searchLicense: true
        )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('cabor.wasit.index', compact('wasits'));
    }

    public function create()
    {
        return view('cabor.wasit.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);
        $caborId = $this->caborId();
        $validated['cabor_id'] = $caborId;
        $validated['slug'] = $this->uniqueSlug($validated['name'], Wasit::class, ['cabor_id' => $caborId]);
        $validated['photo'] = $this->storeUpload($request->file('photo'), 'wasits');
        $validated['is_active'] = $request->boolean('is_active', true);

        Wasit::create($validated);

        return redirect(cabor_route('cabor.wasit.index'))->with('success', 'Wasit berhasil ditambahkan.');
    }

    public function edit(Wasit $wasit)
    {
        $this->assertModelBelongsToCabor($wasit);

        return view('cabor.wasit.edit', compact('wasit'));
    }

    public function update(Request $request, Wasit $wasit)
    {
        $this->assertModelBelongsToCabor($wasit);

        $validated = $this->validated($request);
        $caborId = $this->caborId();
        $validated['cabor_id'] = $caborId;

        if ($wasit->name !== $validated['name']) {
            $validated['slug'] = $this->uniqueSlug($validated['name'], Wasit::class, ['cabor_id' => $caborId], $wasit->id);
        }

        $validated['photo'] = $this->storeUpload($request->file('photo'), 'wasits', $wasit->photo);
        $validated['is_active'] = $request->boolean('is_active');

        $wasit->update($validated);

        return redirect(cabor_route('cabor.wasit.index'))->with('success', 'Wasit berhasil diperbarui.');
    }

    public function destroy(Wasit $wasit)
    {
        $this->assertModelBelongsToCabor($wasit);

        if ($wasit->photo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($wasit->photo);
        }

        $wasit->delete();

        return redirect(cabor_route('cabor.wasit.index'))->with('success', 'Wasit berhasil dihapus.');
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
