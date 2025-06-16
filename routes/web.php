<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AduanController;
use App\Models\Aduan;

// Grup route yang memerlukan autentikasi
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {

    // Dashboard utama
    Route::get('/dashboard', function () {
        $aduans = Aduan::with('user', 'kategori')->latest()->get();
        return view('dashboard', compact('aduans'));
    })->name('dashboard');

    // Form tambah aduan
    Route::get('/aduan/create', [AduanController::class, 'create'])->name('aduan.create');

    // Proses simpan aduan
    Route::post('/aduan', [AduanController::class, 'store'])->name('aduan.store');
});
