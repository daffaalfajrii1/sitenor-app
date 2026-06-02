<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->needsProfileCompletion()) {
            $user->loadMissing('cabor');

            return redirect(cabor_route('cabor.profile.complete', ['cabor' => $user->cabor]));
        }

        return $next($request);
    }
}
