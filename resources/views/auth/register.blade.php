@extends('layouts.auth')

@section('title', 'Daftar')
@section('auth-main-class', 'sitenor-auth-main--wide')

@php
    $duralux = asset('duralux/assets');
    $site = $siteSettings ?? null;
    $occupiedCaborIds = $occupiedCaborIds ?? [];
@endphp

@push('styles')
<link rel="stylesheet" href="{{ $duralux }}/vendors/css/select2.min.css">
<link rel="stylesheet" href="{{ $duralux }}/vendors/css/select2-theme.min.css">
@endpush

@section('content')
<div class="sitenor-auth-wrap sitenor-auth-wrap--wide">
    <div class="sitenor-auth-card">
        <div class="sitenor-auth-card__accent"></div>
        <div class="sitenor-auth-card__header">
            @if($site?->logoUrl())
                <img src="{{ $site->logoUrl() }}" alt="{{ $site->app_name }}">
            @endif
            <h1 class="sitenor-auth-card__title">Daftar Kepala Cabor</h1>
            <p class="sitenor-auth-card__subtitle">{{ $site->app_name ?? 'Sitenor Rejang Lebong' }}</p>
        </div>
        <div class="sitenor-auth-card__body">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-12">
                        <label>Nama lengkap *</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-12">
                        <label>Email *</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="email">
                    </div>
                    <div class="col-12">
                        <label>Cabang olahraga *</label>
                        <select name="cabor_id" id="caborSelect" class="form-select sitenor-cabor-select @error('cabor_id') is-invalid @enderror" required>
                            <option value=""></option>
                            @foreach($cabors as $c)
                                @php $isOccupied = in_array($c->id, $occupiedCaborIds, true); @endphp
                                <option
                                    value="{{ $c->id }}"
                                    @selected(old('cabor_id') == $c->id)
                                    @disabled($isOccupied)
                                    data-occupied="{{ $isOccupied ? '1' : '0' }}"
                                >
                                    {{ $c->name }}{{ $c->kode ? ' ('.$c->kode.')' : '' }}{{ $isOccupied ? ' — sudah ada admin' : '' }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-muted small mt-2 mb-0">Ketik untuk mencari. Cabor yang sudah ada adminnya tidak bisa dipilih.</p>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label>Password *</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password">
                    </div>
                    <div class="col-12 col-sm-6">
                        <label>Konfirmasi password *</label>
                        <input type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
                    </div>
                </div>
                <button type="submit" class="btn sitenor-auth-submit w-100 mt-4" @disabled($cabors->isEmpty())>Daftar Sekarang</button>
            </form>

            <p class="text-center fs-12 text-muted mt-4 mb-0">
                Sudah punya akun? <a href="{{ route('login') }}" class="sitenor-auth-card__link fw-semibold">Masuk di sini</a>
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ $duralux }}/vendors/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const el = document.getElementById('caborSelect');
    if (!el || typeof jQuery === 'undefined' || !jQuery.fn.select2) {
        return;
    }

    jQuery(el).select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: 'Cari atau pilih cabang olahraga…',
        allowClear: true,
        dropdownParent: jQuery(el).closest('.sitenor-auth-card'),
        language: {
            noResults: function () {
                return 'Tidak ditemukan';
            },
            searching: function () {
                return 'Mencari…';
            },
            inputTooShort: function () {
                return 'Ketik untuk mencari';
            }
        },
        templateResult: function (data) {
            if (!data.id) {
                return data.text;
            }
            const occupied = jQuery(data.element).data('occupied') === 1 || jQuery(data.element).data('occupied') === '1';
            if (occupied) {
                return jQuery('<span class="text-muted">' + data.text + '</span>');
            }
            return data.text;
        }
    });
});
</script>
@endpush
