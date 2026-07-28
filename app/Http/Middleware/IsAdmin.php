<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Jika BELUM LOGIN sama sekali -> Lempar ke Login Admin
        if (!Auth::check()) {
            return redirect()->route('admin.login')->with('error', 'Silakan login sebagai admin terlebih dahulu.');
        }

        // 2. Jika SUDAH LOGIN tapi BUKAN ADMIN (misal akun User biasa) -> Lempar ke Login Admin
        if (Auth::user()->role !== 'admin') {
            Auth::logout(); // Log out sesi user biasa agar tidak nyangkut
            return redirect()->route('admin.login')->with('error', 'Akses ditolak! Anda bukan Admin.');
        }

        // 3. Jika lolos (Sudah login & Role == 'admin') -> Silakan masuk
        return $next($request);
    }
}