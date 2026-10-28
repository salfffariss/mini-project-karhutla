<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Arahkan halaman utama langsung ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Autentikasi
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Placeholder sementara untuk dashboard
Route::get('/dashboard', function () {
    return '
        <div style="font-family: sans-serif; padding: 40px; background: #fff; color: #111;">
            <h2 style="font-size: 20px; font-weight: bold;">Login Berhasil!</h2>
            <p>Selamat datang, <b>' . auth()->user()->nama_lengkap . '</b> (' . auth()->user()->role . ')</p>
            <form action="' . route('logout') . '" method="POST" style="margin-top: 20px;">
                ' . csrf_field() . '
                <button type="submit" style="padding: 8px 16px; background: #111; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Keluar (Logout)</button>
            </form>
        </div>
    ';
})->middleware('auth')->name('dashboard');