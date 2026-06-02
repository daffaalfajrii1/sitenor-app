@extends('cabor.layouts.app')

@section('title', 'Lengkapi Profil')

@section('content')
@include('admin.components.page-header', [
    'title' => 'Lengkapi Profil',
    'crumbs' => [['label' => 'Profil']],
])

<div class="main-content">
    <div class="alert alert-info border-0 shadow-sm mb-4">
        Foto profil, telepon, dan media sosial bersifat opsional. Anda bisa mengisi sekarang atau nanti lewat menu Profil Saya.
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h6 class="mb-0">Data Profil</h6>
            <form action="{{ cabor_route('cabor.profile.complete.skip') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-light">Lewati ke dashboard</button>
            </form>
        </div>
        <div class="card-body">
            @if($user->cabor)
                <p class="text-muted fs-12 mb-3">
                    <strong>Cabang olahraga:</strong> {{ $user->cabor->name }}
                    @if($user->cabor->kode)<span class="text-muted">({{ $user->cabor->kode }})</span>@endif
                </p>
            @endif

            <form action="{{ cabor_route('cabor.profile.complete.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('cabor.profile._form', [
                    'user' => $user,
                    'requirePhone' => false,
                    'requireAvatar' => false,
                ])
                <div class="sitenor-form-actions mt-4">
                    <button type="submit" class="btn btn-primary sitenor-btn-action">
                        <i class="feather-save me-2"></i>Simpan & ke Dashboard
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
