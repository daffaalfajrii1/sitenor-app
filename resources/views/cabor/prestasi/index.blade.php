@extends('cabor.layouts.app')
@section('title', 'Prestasi Atlet')
@section('content')
@include('admin.components.page-header', [
    'title' => 'Prestasi Atlet',
    'crumbs' => [['label' => 'Prestasi Atlet']],
    'actions' => '<a href="'.cabor_route('cabor.prestasi.create').'" class="btn btn-primary"><i class="feather-plus me-2"></i>Tambah Prestasi</a>',
])
<div class="main-content">
    @include('admin.components.list-filters', [
        'showCabor' => false,
        'showStatus' => false,
        'showAtlet' => true,
        'atletOptions' => $atletOptions,
        'searchPlaceholder' => 'Cari nama atlet...',
    ])
    <div class="card sitenor-table-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table sitenor-data-table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th style="width:50px">No</th>
                            <th>Nama Atlet</th>
                            <th class="text-center" style="width:100px">Jumlah Prestasi</th>
                            <th class="text-end" style="min-width:200px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($atlets as $atlet)
                        <tr>
                            <td class="text-muted">{{ $atlets->firstItem() + $loop->index }}</td>
                            <td>
                                <a href="{{ cabor_route('cabor.prestasi.atlet', ['atlet' => $atlet]) }}" class="sitenor-cell-name text-decoration-none">
                                    {{ $atlet->name }}
                                </a>
                            </td>
                            <td class="text-center">
                                <a href="{{ cabor_route('cabor.prestasi.atlet', ['atlet' => $atlet]) }}" class="sitenor-count-badge {{ $atlet->prestasis_count ? 'bg-soft-primary text-primary' : 'bg-soft-secondary text-secondary' }} text-decoration-none">
                                    {{ $atlet->prestasis_count }}
                                </a>
                            </td>
                            <td class="text-end">
                                <x-admin.components.table-actions>
                                    <a href="{{ cabor_route('cabor.prestasi.atlet', ['atlet' => $atlet]) }}" class="btn btn-sm btn-light-brand">Lihat</a>
                                    <a href="{{ cabor_route('cabor.prestasi.create', ['atlet_id' => $atlet->id]) }}" class="btn btn-sm btn-primary">+ Tambah</a>
                                </x-admin.components.table-actions>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-5">
                                Belum ada atlet. Tambah atlet dulu di menu <a href="{{ cabor_route('cabor.atlet.index') }}">Data Atlet</a>.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3 border-top">{{ $atlets->links() }}</div>
        </div>
    </div>
</div>
@endsection
