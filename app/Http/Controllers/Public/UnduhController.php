<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class UnduhController extends Controller
{
    public function index(Request $request)
    {
        $pengumumans = Pengumuman::query()
            ->where('is_published', true)
            ->when($request->search, fn ($q, $s) => $q->where('title', 'like', "%{$s}%"))
            ->latest('published_at')
            ->paginate(15)
            ->withQueryString();

        return view('public.unduh.index', compact('pengumumans'));
    }
}
