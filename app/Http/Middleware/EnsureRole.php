<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Batasi route berdasarkan role. Pakai: ->middleware('role:admin,superadmin')
 * Jalankan SETELAH middleware 'auth'.
 */
class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->guest(route('auth.login'));
        }

        if (! $user->punyaRole(...$roles)) {
            if ($request->expectsJson()) {
                abort(403);
            }

            // Admin yang salah masuk area superadmin kembali ke dashboard, pelanggan ke toko.
            return redirect()
                ->route($user->isStaf() ? 'admin.dashboard' : 'toko.index')
                ->with('toast', ['type' => 'error', 'message' => 'Kamu tidak punya akses ke halaman itu.']);
        }

        $response = $next($request);

        // Halaman admin jangan di-cache, supaya tombol "kembali" setelah logout tidak menampilkannya lagi.
        $response->headers->set('Cache-Control', 'no-store, private');

        return $response;
    }
}