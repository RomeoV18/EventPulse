<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Membatasi rute berdasarkan peran. Pemakaian di rute:
 *   ->middleware(EnsureRole::class . ':panitia')
 */
class EnsureRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (! $user || $user->role !== $role) {
            abort(403, 'Halaman ini hanya untuk ' . $role . '.');
        }

        return $next($request);
    }
}
