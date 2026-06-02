<?php

use App\Http\Controllers\Cabor\ArtikelController;
use App\Http\Controllers\Cabor\AtletController;
use App\Http\Controllers\Cabor\CaborExcelController;
use App\Http\Controllers\Cabor\DashboardController;
use App\Http\Controllers\Cabor\JuriController;
use App\Http\Controllers\Cabor\PelatihController;
use App\Http\Controllers\Cabor\PrestasiController;
use App\Http\Controllers\Cabor\ProfileController;
use App\Http\Controllers\Cabor\WasitController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin cabor', 'cabor.panel'])
    ->prefix('kepala-cabor/{cabor}')
    ->name('cabor.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('profile/complete', [ProfileController::class, 'complete'])->name('profile.complete');
        Route::post('profile/complete', [ProfileController::class, 'storeComplete'])->name('profile.complete.store');
        Route::post('profile/complete/skip', [ProfileController::class, 'skipComplete'])->name('profile.complete.skip');

        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

        Route::get('excel/{module}/template', [CaborExcelController::class, 'template'])->name('excel.template');
        Route::post('excel/{module}/import', [CaborExcelController::class, 'import'])->name('excel.import');

        Route::get('prestasi/show/{atlet}', [PrestasiController::class, 'atlet'])
            ->name('prestasi.atlet')
            ->whereNumber('atlet');

        Route::resource('atlet', AtletController::class)->except(['show']);
        Route::resource('pelatih', PelatihController::class)->except(['show']);
        Route::resource('wasit', WasitController::class)->except(['show']);
        Route::resource('juri', JuriController::class)->except(['show']);
        Route::resource('prestasi', PrestasiController::class)->except(['show']);
        Route::post('artikel/editor-upload', [ArtikelController::class, 'uploadEditorImage'])
            ->name('artikel.editor-upload');
        Route::resource('artikel', ArtikelController::class)->except(['show']);
    });
