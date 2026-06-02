<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Cabor;
use App\Models\Juri;
use App\Models\Wasit;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WasitJuriController extends Controller
{
    public function index(Request $request)
    {
        $wasits = Wasit::query()
            ->with('cabor')
            ->where('is_active', true)
            ->when($request->cabor_id, fn ($q) => $q->where('cabor_id', $request->cabor_id))
            ->when($request->search, fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->orderBy('name')
            ->paginate(12, ['*'], 'wasit_page')
            ->withQueryString();

        $juris = Juri::query()
            ->with('cabor')
            ->where('is_active', true)
            ->when($request->cabor_id, fn ($q) => $q->where('cabor_id', $request->cabor_id))
            ->when($request->search, fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->orderBy('name')
            ->paginate(12, ['*'], 'juri_page')
            ->withQueryString();

        return view('public.wasit-juri.index', [
            'wasits' => $wasits,
            'juris' => $juris,
            'cabors' => Cabor::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function showWasit(Request $request, Wasit $wasit): View
    {
        abort_unless($wasit->is_active, 404);

        $wasit->load('cabor');

        return $this->personDetailResponse($request, $wasit, 'wasit', [
            'Level' => $wasit->level_label,
            'No. Lisensi' => $wasit->license_number,
            'Telepon' => $wasit->phone,
        ]);
    }

    public function showJuri(Request $request, Juri $juri): View
    {
        abort_unless($juri->is_active, 404);

        $juri->load('cabor');

        return $this->personDetailResponse($request, $juri, 'juri', [
            'Level' => $juri->level_label,
            'No. Lisensi' => $juri->license_number,
            'Telepon' => $juri->phone,
        ]);
    }

    /**
     * @param  array<string, string|null>  $fields
     */
    protected function personDetailResponse(Request $request, Wasit|Juri $person, string $type, array $fields): View
    {
        if (! $request->ajax() && ! $request->header('X-Requested-With')) {
            abort(404);
        }

        return view('public.partials.person-detail', [
            'person' => $person,
            'type' => $type,
            'fields' => $fields,
        ]);
    }
}
