@extends('cabor.layouts.app')
@section('title', 'Dashboard')
@section('content')
@include('admin.components.page-header', [
    'title' => 'Dashboard Kepala Cabor',
    'crumbs' => [['label' => 'Dashboard']],
])
<div class="main-content">
    @if($cabor)
    <div class="alert alert-info mb-4">Anda mengelola data untuk <strong>{{ $cabor->name }}</strong> ({{ $cabor->kode ?? '-' }}).</div>
    @endif
    <div class="row g-3">
        @foreach([
            ['label' => 'Atlet', 'count' => $stats['atlet'], 'icon' => 'feather-user', 'route' => 'cabor.atlet.index'],
            ['label' => 'Pelatih', 'count' => $stats['pelatih'], 'icon' => 'feather-users', 'route' => 'cabor.pelatih.index'],
            ['label' => 'Wasit', 'count' => $stats['wasit'], 'icon' => 'feather-shield', 'route' => 'cabor.wasit.index'],
            ['label' => 'Juri', 'count' => $stats['juri'], 'icon' => 'feather-eye', 'route' => 'cabor.juri.index'],
            ['label' => 'Prestasi', 'count' => $stats['prestasi'], 'icon' => 'feather-award', 'route' => 'cabor.prestasi.index'],
            ['label' => 'Artikel', 'count' => $stats['artikel'], 'icon' => 'feather-file-text', 'route' => 'cabor.artikel.index'],
        ] as $stat)
        <div class="col-md-4 col-lg-2">
            <a href="{{ cabor_route($stat['route']) }}" class="card text-decoration-none h-100">
                <div class="card-body text-center">
                    <i class="{{ $stat['icon'] }} fs-3 text-primary mb-2"></i>
                    <h4 class="mb-0">{{ $stat['count'] }}</h4>
                    <small class="text-muted">{{ $stat['label'] }}</small>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection
