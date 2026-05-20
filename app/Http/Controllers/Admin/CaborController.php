<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Models\Cabor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CaborController extends Controller
{
    use HandlesUploads;

    public function index()
    {
        $cabors = Cabor::query()
            ->withCount(['atlets', 'pelatihs', 'wasits', 'prestasis'])
            ->orderBy('name')
            ->paginate(15);

        return view('admin.cabor.index', compact('cabors'));
    }

    public function create()
    {
        return view('admin.cabor.create', [
            'suggestedKode' => Cabor::generateKode(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:30', 'alpha_dash', 'unique:cabors,kode'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['kode'] = strtoupper($validated['kode']);
        $validated['slug'] = $this->uniqueSlug($validated['name'], Cabor::class);
        $validated['logo'] = $this->storeUpload($request->file('logo'), 'cabors');
        $validated['is_active'] = $request->boolean('is_active', true);

        Cabor::create($validated);

        return redirect()
            ->route('admin.cabor.index')
            ->with('success', 'Cabang olahraga berhasil ditambahkan.');
    }

    public function edit(Cabor $cabor)
    {
        return view('admin.cabor.edit', compact('cabor'));
    }

    public function update(Request $request, Cabor $cabor)
    {
        $validated = $request->validate([
            'kode' => [
                'required',
                'string',
                'max:30',
                'alpha_dash',
                Rule::unique('cabors', 'kode')->ignore($cabor->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['kode'] = strtoupper($validated['kode']);

        if ($cabor->name !== $validated['name']) {
            $validated['slug'] = $this->uniqueSlug($validated['name'], Cabor::class, [], $cabor->id);
        }

        $validated['logo'] = $this->storeUpload($request->file('logo'), 'cabors', $cabor->logo);
        $validated['is_active'] = $request->boolean('is_active');

        $cabor->update($validated);

        return redirect()
            ->route('admin.cabor.index')
            ->with('success', 'Cabang olahraga berhasil diperbarui.');
    }

    public function destroy(Cabor $cabor)
    {
        if ($cabor->logo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($cabor->logo);
        }

        $cabor->delete();

        return redirect()
            ->route('admin.cabor.index')
            ->with('success', 'Cabang olahraga berhasil dihapus.');
    }
}
