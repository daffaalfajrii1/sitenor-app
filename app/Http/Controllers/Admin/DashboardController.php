<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\Atlet;
use App\Models\Cabor;
use App\Models\Pelatih;
use App\Models\Pengumuman;
use App\Models\Wasit;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'stats' => [
                'cabor' => Cabor::count(),
                'atlet' => Atlet::count(),
                'pelatih' => Pelatih::count(),
                'wasit' => Wasit::count(),
                'artikel' => Artikel::count(),
                'pengumuman' => Pengumuman::count(),
            ],
            'recentCabors' => Cabor::latest()->take(5)->get(),
        ]);
    }
}
