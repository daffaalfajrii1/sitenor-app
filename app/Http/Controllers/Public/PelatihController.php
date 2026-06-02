<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Cabor;
use App\Models\Pelatih;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PelatihController extends Controller
{
    public function index(Request $request)
    {
        $pelatihs = Pelatih::query()
            ->with('cabor')
            ->where('is_active', true)
            ->when($request->cabor_id, fn ($q) => $q->where('cabor_id', $request->cabor_id))
            ->when($request->search, fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('public.pelatih.index', [
            'pelatihs' => $pelatihs,
            'cabors' => Cabor::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function show(Request $request, Pelatih $pelatih): View
    {
        abort_unless($pelatih->is_active, 404);

        $pelatih->load('cabor');

        if (! $request->ajax() && ! $request->header('X-Requested-With')) {
            abort(404);
        }

        return view('public.partials.person-detail', [
            'person' => $pelatih,
            'type' => 'pelatih',
            'fields' => [
                'Level' => $pelatih->level_label,
                'No. Lisensi' => $pelatih->license_number,
                'Telepon' => $pelatih->phone,
                'Email' => $pelatih->email,
            ],
        ]);
    }
}
