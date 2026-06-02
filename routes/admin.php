<?php

use App\Http\Controllers\Admin\AdminExcelController;
use App\Http\Controllers\Admin\ArtikelController;
use App\Http\Controllers\Admin\AtletController;
use App\Http\Controllers\Admin\CaborController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JuriController;
use App\Http\Controllers\Admin\KepalaCaborController;
use App\Http\Controllers\Admin\PelatihController;
use App\Http\Controllers\Admin\PengumumanController;
use App\Http\Controllers\Admin\PrestasiController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\SuperAdminController;
use App\Http\Controllers\Admin\WasitController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:super admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

        Route::get('settings', [SiteSettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SiteSettingController::class, 'update'])->name('settings.update');

        Route::resource('super-admin', SuperAdminController::class)
            ->parameters(['super-admin' => 'superAdmin'])
            ->except(['show']);

        Route::get('excel/{module}/template', [AdminExcelController::class, 'template'])->name('excel.template');
        Route::post('excel/{module}/import', [AdminExcelController::class, 'import'])->name('excel.import');

        Route::resource('cabor', CaborController::class)->except(['show']);
        Route::resource('kepala-cabor', KepalaCaborController::class)
            ->parameters(['kepala-cabor' => 'kepalaCabor'])
            ->except(['show']);
        Route::resource('atlet', AtletController::class)->except(['show']);
        Route::resource('pelatih', PelatihController::class)->except(['show']);
        Route::resource('wasit', WasitController::class)->except(['show']);
        Route::resource('juri', JuriController::class)->except(['show']);
        Route::post('artikel/editor-upload', [ArtikelController::class, 'uploadEditorImage'])
            ->name('artikel.editor-upload');
        Route::get('artikel/rilis', [ArtikelController::class, 'released'])->name('artikel.released');
        Route::resource('artikel', ArtikelController::class)->except(['show']);
        Route::resource('pengumuman', PengumumanController::class)->except(['show']);
        Route::get('prestasi/show/{atlet}', [PrestasiController::class, 'atlet'])
            ->name('prestasi.atlet')
            ->whereNumber('atlet');
        Route::resource('prestasi', PrestasiController::class)->except(['show']);
    });
