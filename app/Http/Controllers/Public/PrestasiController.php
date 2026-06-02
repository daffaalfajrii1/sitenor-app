<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Cabor;
use App\Models\Prestasi;
use Illuminate\Http\Request;

class PrestasiController extends Controller
{
    public function index(Request $request)
    {
        $prestasis = Prestasi::query()
            ->with(['atlet.cabor'])
            ->whereHas('atlet', fn ($q) => $q->where('is_active', true))
            ->when($request->cabor_id, function ($q) use ($request) {
                $q->whereHas('atlet', fn ($a) => $a->where('cabor_id', $request->cabor_id));
            })
            ->when($request->level, fn ($q) => $q->where('level', $request->level))
            ->when($request->search, function ($q, $s) {
                $q->where(function ($q) use ($s) {
                    $q->where('title', 'like', "%{$s}%")
                        ->orWhere('event_name', 'like', "%{$s}%")
                        ->orWhereHas('atlet', fn ($a) => $a->where('name', 'like', "%{$s}%"));
                });
            })
            ->latest('year')
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        return view('public.prestasi.index', [
            'prestasis' => $prestasis,
            'cabors' => Cabor::where('is_active', true)->orderBy('name')->get(),
            'levels' => Prestasi::levelOptions(),
        ]);
    }
}
