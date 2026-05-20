<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Models\Atlet;
use App\Models\Cabor;
use Illuminate\Http\Request;

class AtletController extends Controller
{
    use HandlesUploads;

    public function index(Request $request)
    {
        $atlets = Atlet::query()
            ->with('cabor')
            ->when($request->cabor_id, fn ($q) => $q->where('cabor_id', $request->cabor_id))
            ->when($request->search, fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $cabors = Cabor::orderBy('name')->get();

        return view('admin.atlet.index', compact('atlets', 'cabors'));
    }

    public function create()
    {
        return view('admin.atlet.create', ['cabors' => Cabor::orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);
        $validated['slug'] = $this->uniqueSlug($validated['name'], Atlet::class, ['cabor_id' => $validated['cabor_id']]);
        $validated['photo'] = $this->storeUpload($request->file('photo'), 'atlets');
        $validated['is_active'] = $request->boolean('is_active', true);

        Atlet::create($validated);

        return redirect()->route('admin.atlet.index')->with('success', 'Atlet berhasil ditambahkan.');
    }

    public function edit(Atlet $atlet)
    {
        return view('admin.atlet.edit', [
            'atlet' => $atlet->load('prestasis'),
            'cabors' => Cabor::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Atlet $atlet)
    {
        $validated = $this->validated($request);

        if ($atlet->name !== $validated['name'] || $atlet->cabor_id != $validated['cabor_id']) {
            $validated['slug'] = $this->uniqueSlug($validated['name'], Atlet::class, ['cabor_id' => $validated['cabor_id']], $atlet->id);
        }

        $validated['photo'] = $this->storeUpload($request->file('photo'), 'atlets', $atlet->photo);
        $validated['is_active'] = $request->boolean('is_active');

        $atlet->update($validated);

        return redirect()->route('admin.atlet.index')->with('success', 'Atlet berhasil diperbarui.');
    }

    public function destroy(Atlet $atlet)
    {
        if ($atlet->photo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($atlet->photo);
        }

        $atlet->delete();

        return redirect()->route('admin.atlet.index')->with('success', 'Atlet berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'cabor_id' => ['required', 'exists:cabors,id'],
            'name' => ['required', 'string', 'max:255'],
            'nik' => ['nullable', 'string', 'max:20'],
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
