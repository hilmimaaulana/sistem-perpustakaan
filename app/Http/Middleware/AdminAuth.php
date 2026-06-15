<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // JIKA TIDAK ADA MEMORI SESSION LOGIN ADMIN, KITA USIR GAIS!
        // Langsung dilempar balik ke halaman login dengan pesan peringatan eror
        if (!session()->has('admin_logged_in')) {
            return redirect()->route('admin.login')->withErrors(['error' => 'Silakan login terlebih dahulu']);
        }

        // Jika lolos (sudah login), izinkan masuk ke dashboard gais
        return $next($request);
    }
}