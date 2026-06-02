@extends('cabor.layouts.app')
@section('title', 'Profil')
@section('content')
@include('admin.components.page-header', ['title' => 'Profil Saya', 'crumbs' => [['label' => 'Profil']]])
<div class="main-content">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header"><h6 class="mb-0">Informasi Profil</h6></div>
                <div class="card-body">
                    <form action="{{ cabor_route('cabor.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        @include('cabor.profile._form', ['user' => $user])
                        <div class="sitenor-form-actions">
                            <button type="submit" class="btn btn-primary sitenor-btn-action">
                                <i class="feather-save me-2"></i>Simpan Profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header"><h6 class="mb-0">Ganti Password</h6></div>
                <div class="card-body">
                    <form action="{{ cabor_route('cabor.profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3"><label>Password saat ini</label><input type="password" name="current_password" class="form-control" required></div>
                        <div class="mb-3"><label>Password baru</label><input type="password" name="password" class="form-control" required></div>
                        <div class="mb-3"><label>Konfirmasi</label><input type="password" name="password_confirmation" class="form-control" required></div>
                        <button type="submit" class="btn btn-light-brand">Ubah Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
