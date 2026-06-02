@extends('layouts.public.app')
@section('title', 'Unduhan')

@section('content')
<div class="sitenor-public-page">
    <div class="container">
        <div class="sitenor-public-page-header">
            <h1>Unduhan</h1>
            <p>Dokumen dan pengumuman resmi yang dapat diunduh.</p>
        </div>
        <form method="GET" class="sitenor-public-filter mb-3">
            <div class="row g-2">
                <div class="col-md-10">
                    <input type="search" name="search" class="form-control" value="{{ request('search') }}" placeholder="Cari judul pengumuman...">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-danger w-100">Cari</button>
                </div>
            </div>
        </form>
        <div class="sitenor-public-card">
            <div class="list-group list-group-flush">
                @forelse ($pengumumans as $p)
                    <div class="list-group-item p-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div>
                            <h2 class="h6 fw-bold mb-1">{{ $p->title }}</h2>
                            @if ($p->description)
                                <p class="text-muted fs-14 mb-1">{{ $p->description }}</p>
                            @endif
                            <span class="text-muted fs-12">
                                {{ $p->published_at?->format('d M Y') }}
                                @if ($p->file_size) · {{ number_format($p->file_size / 1024, 1) }} KB @endif
                            </span>
                        </div>
                        <a href="{{ $p->downloadUrl() }}" class="btn btn-danger" download>
                            <i class="feather-download me-1"></i> Unduh
                        </a>
                    </div>
                @empty
                    <div class="p-5 text-center text-muted">Belum ada file unduhan.</div>
                @endforelse
            </div>
            @if ($pengumumans->hasPages())
                <div class="p-3 border-top">{{ $pengumumans->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
