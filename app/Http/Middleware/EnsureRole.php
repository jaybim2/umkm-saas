<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        if ($role === 'vendor') {
            if (!$request->user()->isVendor()) {
                abort(403, 'Akses ditolak: Area ini khusus untuk Vendor Platform.');
            }
        }

        if ($role === 'umkm_admin') {
            if (!$request->user()->isUmkmAdmin()) {
                if ($request->user()->isVendor()) {
                    return redirect()->route('vendor.dashboard');
                }
                abort(403, 'Akses ditolak: Area ini khusus untuk Admin UMKM.');
            }
        }

        return $next($request);
    }
}

