<?php

namespace App\Http\Controllers\Cabor;

use App\Http\Controllers\Concerns\HandlesArtikelEditorUpload;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Http\Controllers\Cabor\Concerns\ScopesToCabor;
use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArtikelController extends Controller
{
    use HandlesArtikelEditorUpload, HandlesUploads, ScopesToCabor;

    public function index(Request $request)
    {
        $artikels = Artikel::query()
            ->where('cabor_id', $this->caborId())
            ->when($request->search, fn ($q, $s) => $q->where('title', 'like', "%{$s}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('cabor.artikel.index', compact('artikels'));
    }

    public function create()
    {
        return view('cabor.artikel.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);
        $validated['cabor_id'] = $this->caborId();
        $validated['slug'] = $this->uniqueSlug($validated['title'], Artikel::class);
        $validated['thumbnail'] = $this->storeUpload($request->file('thumbnail'), 'artikels');
        $validated['user_id'] = Auth::id();
        [$validated['is_published'], $validated['published_at']] = $this->resolvePublishState($request);

        Artikel::create($validated);

        $message = $validated['is_published']
            ? 'Artikel berhasil diterbitkan.'
            : 'Draft artikel berhasil disimpan.';

        return redirect(cabor_route('cabor.artikel.index'))->with('success', $message);
    }

    public function edit(Artikel $artikel)
    {
        $this->assertModelBelongsToCabor($artikel);

        return view('cabor.artikel.edit', compact('artikel'));
    }

    public function update(Request $request, Artikel $artikel)
    {
        $this->assertModelBelongsToCabor($artikel);

        $validated = $this->validated($request);
        $validated['cabor_id'] = $this->caborId();

        if ($artikel->title !== $validated['title']) {
            $validated['slug'] = $this->uniqueSlug($validated['title'], Artikel::class, [], $artikel->id);
        }

        $validated['thumbnail'] = $this->storeUpload($request->file('thumbnail'), 'artikels', $artikel->thumbnail);
        [$validated['is_published'], $validated['published_at']] = $this->resolvePublishState($request, $artikel);

        $artikel->update($validated);

        $message = $validated['is_published']
            ? 'Artikel berhasil diperbarui dan diterbitkan.'
            : 'Draft artikel berhasil disimpan.';

        return redirect(cabor_route('cabor.artikel.index'))->with('success', $message);
    }

    public function destroy(Artikel $artikel)
    {
        $this->assertModelBelongsToCabor($artikel);

        if ($artikel->thumbnail) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($artikel->thumbnail);
        }

        $artikel->delete();

        return redirect(cabor_route('cabor.artikel.index'))->with('success', 'Artikel berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
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
