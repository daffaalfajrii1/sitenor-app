@extends('admin.layouts.app')

@section('title', 'Pengaturan Website')

@section('content')
    @include('admin.components.page-header', [
        'title' => 'Pengaturan Website',
        'crumbs' => [
            ['label' => 'Pengaturan Website'],
        ],
    ])

    <div class="main-content">
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header"><h6 class="mb-0">Logo & Identitas</h6></div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                @if ($settings->logoUrl())
                                    <img src="{{ $settings->logoUrl() }}" alt="Logo" class="img-fluid mb-2" style="max-height: 80px;">
                                @else
                                    <div class="text-muted fs-12 mb-2">Belum ada logo</div>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label class="fw-semibold">Logo Website</label>
                                <input type="file" name="logo" class="form-control" accept="image/*">
                                <div class="form-text">PNG/JPG, maks. 2 MB (opsional)</div>
                                @if ($settings->logo)
                                    <div class="form-check mt-2">
                                        <input type="checkbox" name="remove_logo" value="1" class="form-check-input" id="remove_logo">
                                        <label for="remove_logo" class="form-check-label">Hapus logo</label>
                                    </div>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label class="fw-semibold">Favicon</label>
                                @if ($settings->faviconUrl())
                                    <div class="mb-2"><img src="{{ $settings->faviconUrl() }}" alt="Favicon" width="32" height="32"></div>
                                @endif
                                <input type="file" name="favicon" class="form-control" accept="image/png,image/x-icon,image/jpeg">
                                <div class="form-text">Opsional. Jika kosong, logo website dipakai sebagai ikon tab browser.</div>
                                @if ($settings->favicon)
                                    <div class="form-check mt-2">
                                        <input type="checkbox" name="remove_favicon" value="1" class="form-check-input" id="remove_favicon">
                                        <label for="remove_favicon" class="form-check-label">Hapus favicon</label>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header"><h6 class="mb-0">Informasi Aplikasi</h6></div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="fw-semibold">Nama Aplikasi *</label>
                                    <input type="text" name="app_name" class="form-control @error('app_name') is-invalid @enderror" value="{{ old('app_name', $settings->app_name) }}" required>
                                    @error('app_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="fw-semibold">Tagline / Slogan</label>
                                    <input type="text" name="tagline" class="form-control" value="{{ old('tagline', $settings->tagline) }}" placeholder="Sistem Informasi Tenaga Olahraga">
                                </div>
                                <div class="col-12">
                                    <label class="fw-semibold">Teks Footer</label>
                                    <input type="text" name="footer_text" class="form-control" value="{{ old('footer_text', $settings->footer_text) }}" placeholder="© 2026 Sitenor Rejang Lebong">
                                    <div class="form-text">Baris kolaborasi Diskominfo Rejang Lebong ditampilkan otomatis di bawah teks ini.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header"><h6 class="mb-0">Visi & Misi</h6></div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="fw-semibold">Visi</label>
                                <textarea name="visi" class="form-control" rows="4" placeholder="Visi organisasi / Dispora">{{ old('visi', $settings->visi) }}</textarea>
                            </div>
                            <div class="mb-0">
                                <label class="fw-semibold">Misi</label>
                                <textarea name="misi" class="form-control" rows="6" placeholder="Satu baris per poin misi">{{ old('misi', $settings->misi) }}</textarea>
                                <div class="form-text">Tips: pisahkan tiap poin misi dengan baris baru.</div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header"><h6 class="mb-0">Kontak & Media Sosial</h6></div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="fw-semibold">Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $settings->email) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="fw-semibold">Telepon</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $settings->phone) }}">
                                </div>
                                <div class="col-12">
                                    <label class="fw-semibold">Alamat</label>
                                    <textarea name="address" class="form-control" rows="2">{{ old('address', $settings->address) }}</textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="fw-semibold">Instagram</label>
                                    <input type="text" name="instagram" class="form-control" value="{{ old('instagram', $settings->instagram) }}" placeholder="https://instagram.com/...">
                                </div>
                                <div class="col-md-4">
                                    <label class="fw-semibold">Facebook</label>
                                    <input type="text" name="facebook" class="form-control" value="{{ old('facebook', $settings->facebook) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="fw-semibold">YouTube</label>
                                    <input type="text" name="youtube" class="form-control" value="{{ old('youtube', $settings->youtube) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="feather-save me-2"></i>Simpan Pengaturan
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
