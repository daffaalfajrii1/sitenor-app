@extends('layouts.auth')

@section('title', 'Masuk')

@section('content')
@php
    $duralux = asset('duralux/assets');
    $site = $siteSettings ?? null;
@endphp
<div class="sitenor-auth-wrap">
    <div class="sitenor-auth-card">
        <div class="sitenor-auth-card__accent"></div>
        <div class="sitenor-auth-card__header">
            @if($site?->logoUrl())
                <img src="{{ $site->logoUrl() }}" alt="{{ $site->app_name }}">
            @else
                <img src="{{ $duralux }}/images/logo-abbr.png" alt="Logo">
            @endif
            <h1 class="sitenor-auth-card__title">Masuk</h1>
            <p class="sitenor-auth-card__subtitle">{{ $site->app_name ?? 'Sitenor Rejang Lebong' }}</p>
        </div>
        <div class="sitenor-auth-card__body">
            <x-auth-session-status class="mb-3" :status="session('status')" />
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••" required>
                </div>
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
                    <div class="form-check mb-0">
                        <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                        <label class="form-check-label" for="remember_me">Ingat saya</label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="sitenor-auth-card__link fs-12">Lupa password?</a>
                    @endif
                </div>
                <button type="submit" class="btn sitenor-auth-submit w-100">Masuk</button>
            </form>

            <p class="text-center fs-12 text-muted mt-4 mb-0">
                Kepala cabor baru? <a href="{{ route('register') }}" class="sitenor-auth-card__link fw-semibold">Daftar di sini</a>
            </p>
        </div>
    </div>
</div>
@endsection
