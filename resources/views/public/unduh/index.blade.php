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
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <x-table-no-th />
                            <th>Judul</th>
                            <th>Tanggal</th>
                            <th>Ukuran</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengumumans as $p)
                            <tr>
                                <x-table-no-td :index="$loop->index" :paginator="$pengumumans" />
                                <td>
                                    <div class="fw-semibold">{{ $p->title }}</div>
                                    @if ($p->description)
                                        <div class="text-muted fs-14 mt-1">{{ $p->description }}</div>
                                    @endif
                                </td>
                                <td class="text-nowrap">{{ $p->published_at?->format('d M Y') ?? '—' }}</td>
                                <td class="text-nowrap">
                                    @if ($p->file_size)
                                        {{ number_format($p->file_size / 1024, 1) }} KB
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ $p->downloadUrl() }}" class="btn btn-sm btn-danger" download>
                                        <i class="bi bi-download me-1"></i> Unduh
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">Belum ada file unduhan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($pengumumans->hasPages())
                <div class="p-3 border-top">{{ $pengumumans->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
