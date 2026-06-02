@extends('cabor.layouts.app')
@section('title', 'Edit Prestasi')
@section('content')
@include('admin.components.page-header', [
    'title' => 'Edit Prestasi',
    'crumbs' => [
        ['label' => 'Prestasi Atlet', 'url' => cabor_route('cabor.prestasi.index')],
        ['label' => $prestasi->atlet->name, 'url' => cabor_route('cabor.prestasi.atlet', ['atlet' => $prestasi->atlet_id])],
        ['label' => 'Edit'],
    ],
])
<div class="main-content">
    <div class="card">
        <div class="card-body">
            <form action="{{ cabor_route('cabor.prestasi.update', $prestasi) }}" method="POST">
                @csrf @method('PUT')
                @include('admin.prestasi._form', ['prestasi' => $prestasi, 'showCaborInAtletSelect' => false])
                <div class="d-flex flex-wrap gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                    <a href="{{ cabor_route('cabor.prestasi.atlet', ['atlet' => $prestasi->atlet_id]) }}" class="btn btn-light">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
@php $duralux = asset('duralux/assets'); @endphp
<link rel="stylesheet" href="{{ $duralux }}/vendors/css/select2.min.css">
<link rel="stylesheet" href="{{ $duralux }}/vendors/css/select2-theme.min.css">
@endpush

@push('scripts')
@php $duralux = asset('duralux/assets'); @endphp
<script src="{{ $duralux }}/vendors/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var el = document.getElementById('prestasi-atlet-select');
    if (!el || typeof jQuery === 'undefined' || !jQuery.fn.select2) return;
    jQuery(el).select2({ width: '100%', placeholder: 'Cari nama atlet...' });
});
</script>
@endpush
