<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesArtikelEditorUpload;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Models\Artikel;
use App\Models\Cabor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArtikelController extends Controller
{
    use HandlesArtikelEditorUpload, HandlesUploads;

    public function released(Request $request)
    {
        $artikels = Artikel::query()
            ->with(['cabor', 'user'])
            ->where('is_published', true)
            ->when($request->cabor_id, fn ($q) => $q->where('cabor_id', $request->cabor_id))
            ->when($request->search, fn ($q, $s) => $q->where('title', 'like', "%{$s}%"))
            ->latest('published_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.artikel.released', [
            'artikels' => $artikels,
            'cabors' => Cabor::orderBy('name')->get(),
        ]);
    }

    public function index(Request $request)
    {
        $artikels = Artikel::query()
            ->with('cabor')
            ->when($request->cabor_id, fn ($q) => $q->where('cabor_id', $request->cabor_id))
            ->when($request->search, fn ($q, $s) => $q->where('title', 'like', "%{$s}%"))
            ->when($request->filled('is_published'), fn ($q) => $q->where('is_published', $request->boolean('is_published')))
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
        [$validated['is_published'], $validated['published_at']] = $this->resolvePublishState($request);

        Artikel::create($validated);

        $message = $validated['is_published']
            ? 'Artikel berhasil diterbitkan.'
            : 'Draft artikel berhasil disimpan.';

        return redirect()->route('admin.artikel.index')->with('success', $message);
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
        [$validated['is_published'], $validated['published_at']] = $this->resolvePublishState($request, $artikel);

        $artikel->update($validated);

        $message = $validated['is_published']
            ? 'Artikel berhasil diperbarui dan diterbitkan.'
            : 'Draft artikel berhasil disimpan.';

        return redirect()->route('admin.artikel.index')->with('success', $message);
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
            'save_action' => ['nullable', 'in:draft,publish'],
        ]);
    }

    /**
     * @return array{0: bool, 1: ?\Illuminate\Support\Carbon}
     */
    private function resolvePublishState(Request $request, ?Artikel $artikel = null): array
    {
        $publish = $request->input('save_action', 'publish') === 'publish';

        if (! $publish) {
            return [false, null];
        }

        $publishedAt = $artikel?->published_at ?? now();

        return [true, $publishedAt];
    }
}
