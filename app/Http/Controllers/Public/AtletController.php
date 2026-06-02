<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Atlet;
use App\Models\Cabor;
use App\Models\Prestasi;
use Illuminate\Http\Request;

class AtletController extends Controller
{
    public function index(Request $request)
    {
        $atlets = Atlet::query()
            ->with('cabor')
            ->withCount('prestasis')
            ->where('is_active', true)
            ->when($request->cabor_id, fn ($q) => $q->where('cabor_id', $request->cabor_id))
            ->when($request->search, fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('public.atlet.index', [
            'atlets' => $atlets,
            'cabors' => Cabor::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function show(Atlet $atlet)
    {
        abort_unless($atlet->is_active, 404);

        $atlet->load([
            'cabor',
            'prestasis' => fn ($q) => $q->latest('year')->latest('id'),
        ]);

        $levelCounts = $atlet->prestasis->groupBy('level')->map->count();

        return view('public.atlet.show', [
            'atlet' => $atlet,
            'levelCounts' => $levelCounts,
            'levels' => Prestasi::levelOptions(),
        ]);
    }
}
