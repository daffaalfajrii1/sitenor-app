<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Models\Artikel;
use App\Models\Cabor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArtikelController extends Controller
{
    use HandlesUploads;

    public function index(Request $request)
    {
        $artikels = Artikel::query()
            ->with('cabor')
            ->when($request->cabor_id, fn ($q) => $q->where('cabor_id', $request->cabor_id))
            ->when($request->search, fn ($q, $s) => $q->where('title', 'like', "%{$s}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.artikel.index', [
            'artikels' => $artikels,
            'cabors' => Cabor::orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.artikel.create', ['cabors' => Cabor::orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);
        $validated['slug'] = $this->uniqueSlug($validated['title'], Artikel::class);
        $validated['thumbnail'] = $this->storeUpload($request->file('thumbnail'), 'artikels');
        $validated['user_id'] = Auth::id();
        $validated['is_published'] = $request->boolean('is_published');
        $validated['published_at'] = $validated['is_published'] ? now() : null;

        Artikel::create($validated);

        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil ditambahkan.');
    }

    public function edit(Artikel $artikel)
    {
        return view('admin.artikel.edit', [
            'artikel' => $artikel,
            'cabors' => Cabor::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Artikel $artikel)
    {
        $validated = $this->validated($request);

        if ($artikel->title !== $validated['title']) {
            $validated['slug'] = $this->uniqueSlug($validated['title'], Artikel::class, [], $artikel->id);
        }

        $validated['thumbnail'] = $this->storeUpload($request->file('thumbnail'), 'artikels', $artikel->thumbnail);
        $validated['is_published'] = $request->boolean('is_published');

        if ($validated['is_published'] && ! $artikel->published_at) {
            $validated['published_at'] = now();
        }

        if (! $validated['is_published']) {
            $validated['published_at'] = null;
        }

        $artikel->update($validated);

        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Artikel $artikel)
    {
        if ($artikel->thumbnail) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($artikel->thumbnail);
        }

        $artikel->delete();

        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'cabor_id' => ['nullable', 'exists:cabors,id'],
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['nullable', 'string'],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'is_published' => ['nullable', 'boolean'],
        ]);
    }
}
