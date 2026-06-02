@extends('cabor.layouts.app')
@section('title', 'Tambah Prestasi')
@section('content')
@php
    $prestasiCrumbs = [['label' => 'Prestasi Atlet', 'url' => cabor_route('cabor.prestasi.index')]];
    if (!empty($selectedAtletId)) {
        $prestasiAtlet = \App\Models\Atlet::find($selectedAtletId);
        if ($prestasiAtlet) {
            $prestasiCrumbs[] = ['label' => $prestasiAtlet->name, 'url' => cabor_route('cabor.prestasi.atlet', ['atlet' => $prestasiAtlet])];
        }
    }
    $prestasiCrumbs[] = ['label' => 'Tambah'];
@endphp
@include('admin.components.page-header', [
    'title' => 'Tambah Prestasi',
    'crumbs' => $prestasiCrumbs,
])
<div class="main-content">
    <div class="card">
        <div class="card-body">
            <p class="text-muted fs-12 mb-4">Pilih atlet terlebih dahulu, lalu isi data prestasi.</p>
            <form action="{{ cabor_route('cabor.prestasi.store') }}" method="POST">
                @csrf
                @include('admin.prestasi._form', ['showCaborInAtletSelect' => false])
                <div class="d-flex flex-wrap gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Simpan Prestasi</button>
                    <a href="{{ $selectedAtletId ? cabor_route('cabor.prestasi.atlet', ['atlet' => $selectedAtletId]) : cabor_route('cabor.prestasi.index') }}" class="btn btn-light">Batal</a>
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
    jQuery(el).select2({ width: '100%', placeholder: 'Cari nama atlet...', allowClear: !el.required });
});
</script>
@endpush
