<?php

namespace App\Http\Controllers\Cabor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Concerns\AppliesListFilters;
use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Http\Controllers\Cabor\Concerns\ScopesToCabor;
use App\Models\Atlet;
use Illuminate\Http\Request;

class AtletController extends Controller
{
    use AppliesListFilters, HandlesUploads, ScopesToCabor;

    public function index(Request $request)
    {
        $atlets = $this->applyCaborPersonFilters(
            Atlet::query()->where('cabor_id', $this->caborId())->withCount('prestasis'),
            $request,
            withGender: true
        )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('cabor.atlet.index', compact('atlets'));
    }

    public function create()
    {
        return view('cabor.atlet.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);
        $caborId = $this->caborId();
        $validated['cabor_id'] = $caborId;
        $validated['slug'] = $this->uniqueSlug($validated['name'], Atlet::class, ['cabor_id' => $caborId]);
        $validated['photo'] = $this->storeUpload($request->file('photo'), 'atlets');
        $validated['is_active'] = $request->boolean('is_active', true);

        Atlet::create($validated);

        return redirect(cabor_route('cabor.atlet.index'))->with('success', 'Atlet berhasil ditambahkan.');
    }

    public function edit(Atlet $atlet)
    {
        $this->assertModelBelongsToCabor($atlet);

        return view('cabor.atlet.edit', ['atlet' => $atlet->load('prestasis')]);
    }

    public function update(Request $request, Atlet $atlet)
    {
        $this->assertModelBelongsToCabor($atlet);

        $validated = $this->validated($request);
        $caborId = $this->caborId();
        $validated['cabor_id'] = $caborId;

        if ($atlet->name !== $validated['name']) {
            $validated['slug'] = $this->uniqueSlug($validated['name'], Atlet::class, ['cabor_id' => $caborId], $atlet->id);
        }

        $validated['photo'] = $this->storeUpload($request->file('photo'), 'atlets', $atlet->photo);
        $validated['is_active'] = $request->boolean('is_active');

        $atlet->update($validated);

        return redirect(cabor_route('cabor.atlet.index'))->with('success', 'Atlet berhasil diperbarui.');
    }

    public function destroy(Atlet $atlet)
    {
        $this->assertModelBelongsToCabor($atlet);

        if ($atlet->photo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($atlet->photo);
        }

        $atlet->delete();

        return redirect(cabor_route('cabor.atlet.index'))->with('success', 'Atlet berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:laki-laki,perempuan'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'bio' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}
