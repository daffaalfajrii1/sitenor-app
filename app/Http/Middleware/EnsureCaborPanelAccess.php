<?php

namespace App\Http\Middleware;

use App\Models\Cabor;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCaborPanelAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user?->cabor_id) {
            abort(403, 'Akun belum terhubung ke cabang olahraga. Hubungi administrator.');
        }

        $cabor = $this->resolveCabor($request);

        if ((int) $cabor->id !== (int) $user->cabor_id) {
            $user->loadMissing('cabor');

            return redirect(cabor_route('cabor.dashboard', ['cabor' => $user->cabor]));
        }

        set_cabor_route_defaults($cabor);

        return $next($request);
    }

    private function resolveCabor(Request $request): Cabor
    {
        $cabor = $request->route('cabor');

        if ($cabor instanceof Cabor) {
            return $cabor;
        }

        $slug = $cabor ?? $request->route('slug');

        if (! is_string($slug) || $slug === '') {
            abort(404);
        }

        return Cabor::query()
            ->where('slug', $slug)
            ->orWhere('id', $slug)
            ->firstOrFail();
    }
}
