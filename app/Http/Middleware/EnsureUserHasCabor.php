<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasCabor
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->user()?->cabor_id) {
            abort(403, 'Akun belum terhubung ke cabang olahraga. Hubungi administrator.');
        }

        return $next($request);
    }
}
