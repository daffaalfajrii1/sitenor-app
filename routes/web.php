<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\ArtikelController as PublicArtikelController;
use App\Http\Controllers\Public\AtletController as PublicAtletController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\PelatihController as PublicPelatihController;
use App\Http\Controllers\Public\PrestasiController as PublicPrestasiController;
use App\Http\Controllers\Public\StatistikController;
use App\Http\Controllers\Public\UnduhController;
use App\Http\Controllers\Public\WasitJuriController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('public.home');
Route::get('/statistik', [StatistikController::class, 'index'])->name('public.statistik');
Route::get('/data/atlet', [PublicAtletController::class, 'index'])->name('public.atlet.index');
Route::get('/data/atlet/{atlet}', [PublicAtletController::class, 'show'])->name('public.atlet.show');
Route::get('/data/pelatih', [PublicPelatihController::class, 'index'])->name('public.pelatih.index');
Route::get('/data/pelatih/{pelatih}', [PublicPelatihController::class, 'show'])->name('public.pelatih.show');
Route::get('/data/wasit-juri', [WasitJuriController::class, 'index'])->name('public.wasit-juri.index');
Route::get('/data/wasit-juri/wasit/{wasit}', [WasitJuriController::class, 'showWasit'])->name('public.wasit.show');
Route::get('/data/wasit-juri/juri/{juri}', [WasitJuriController::class, 'showJuri'])->name('public.juri.show');
Route::get('/data/prestasi', [PublicPrestasiController::class, 'index'])->name('public.prestasi.index');
Route::get('/artikel', [PublicArtikelController::class, 'index'])->name('public.artikel.index');
Route::get('/artikel/{artikel}', [PublicArtikelController::class, 'show'])->name('public.artikel.show');
Route::get('/unduh', [UnduhController::class, 'index'])->name('public.unduh.index');

Route::middleware('auth')->group(function () {
    Route::get('/cabor', function () {
        $cabor = auth()->user()?->cabor;
        abort_unless($cabor, 403);

        return redirect(cabor_route('cabor.dashboard', $cabor));
    });

    Route::get('/kepala-cabor', function () {
        $cabor = auth()->user()?->cabor;
        abort_unless($cabor, 403);

        return redirect(cabor_route('cabor.dashboard', $cabor));
    });
});

Route::get('/dashboard', function () {
    return redirect(auth_home_redirect());
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
