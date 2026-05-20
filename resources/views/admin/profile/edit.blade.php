@extends('admin.layouts.app')

@section('title', 'Profil Saya')

@section('content')
    @include('admin.components.page-header', [
        'title' => 'Profil Saya',
        'crumbs' => [
            ['label' => 'Profil'],
        ],
    ])

    <div class="main-content">
        <div class="row g-4">
            {{-- Foto profil --}}
            <div class="col-lg-4" id="foto">
                <div class="card h-100">
                    <div class="card-header">
                        <h6 class="mb-0">Foto Profil</h6>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            @if ($user->avatarUrl())
                                <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" class="rounded-circle border" width="120" height="120" style="object-fit: cover;">
                            @else
                                <span class="avatar-text avatar-xxl rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width:120px;height:120px;">
                                    <i class="feather-user fs-1"></i>
                                </span>
                            @endif
                        </div>
                        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="name" value="{{ $user->name }}">
                            <input type="hidden" name="email" value="{{ $user->email }}">
                            <div class="mb-3 text-start">
                                <label for="avatar" class="form-label fw-semibold">Unggah foto</label>
                                <input type="file" id="avatar" name="avatar" class="form-control" accept="image/*">
                                <div class="form-text">JPG/PNG, maks. 2 MB</div>
                            </div>
                            @if ($user->avatar)
                                <div class="mb-3 text-start">
                                    <div class="form-check">
                                        <input type="checkbox" id="remove_avatar" name="remove_avatar" value="1" class="form-check-input">
                                        <label for="remove_avatar" class="form-check-label">Hapus foto saat ini</label>
                                    </div>
                                </div>
                            @endif
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="feather-upload me-2"></i>Simpan Foto
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Info & password --}}
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Informasi Akun</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.profile.update') }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="fw-semibold">Nama</label>
                                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="fw-semibold">Email</label>
                                    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">
                                <i class="feather-save me-2"></i>Simpan Perubahan
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card" id="password">
                    <div class="card-header">
                        <h6 class="mb-0">Ganti Password</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.profile.password') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="current_password" class="fw-semibold">Password Saat Ini</label>
                                    <input type="password" id="current_password" name="current_password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" required>
                                    @error('current_password', 'updatePassword')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="password" class="fw-semibold">Password Baru</label>
                                    <input type="password" id="password" name="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" required>
                                    @error('password', 'updatePassword')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="fw-semibold">Konfirmasi Password</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-light-brand mt-3">
                                <i class="feather-lock me-2"></i>Update Password
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
