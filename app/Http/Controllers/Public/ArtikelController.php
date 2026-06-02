<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\Cabor;
use Illuminate\Http\Request;

class ArtikelController extends Controller
{
    public function index(Request $request)
    {
        $artikels = Artikel::query()
            ->with('cabor')
            ->where('is_published', true)
            ->when($request->cabor_id, fn ($q) => $q->where('cabor_id', $request->cabor_id))
            ->when($request->search, fn ($q, $s) => $q->where('title', 'like', "%{$s}%"))
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('public.artikel.index', [
            'artikels' => $artikels,
            'cabors' => Cabor::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function show(Artikel $artikel)
    {
        abort_unless($artikel->is_published, 404);

        $artikel->load(['cabor', 'user']);

        return view('public.artikel.show', [
            'artikel' => $artikel,
            'related' => Artikel::query()
                ->where('is_published', true)
                ->where('id', '!=', $artikel->id)
                ->when($artikel->cabor_id, fn ($q) => $q->where('cabor_id', $artikel->cabor_id))
                ->latest('published_at')
                ->take(3)
                ->get(),
        ]);
    }
}
