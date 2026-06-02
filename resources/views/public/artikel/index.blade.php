@extends('layouts.public.app')
@section('title', 'Artikel')

@section('content')
<div class="sitenor-public-page">
    <div class="container">
        <div class="sitenor-public-page-header">
            <h1>Artikel</h1>
            <p>Berita dan informasi keolahragaan Rejang Lebong.</p>
        </div>
        @include('public.partials.data-filter', ['cabors' => $cabors, 'searchPlaceholder' => 'Cari judul artikel...'])
        <div class="row g-3">
            @forelse ($artikels as $artikel)
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('public.artikel.show', $artikel) }}" class="sitenor-artikel-card">
                    <div class="sitenor-artikel-card__body">
                        <div class="sitenor-artikel-card__meta">
                            {{ $artikel->cabor?->name ?? 'Umum' }}
                            · {{ $artikel->published_at?->format('d M Y') }}
                        </div>
                        <div class="sitenor-artikel-card__title">{{ $artikel->title }}</div>
                        <p class="text-muted fs-14 mb-0">{{ Str::limit($artikel->excerpt, 120) }}</p>
                    </div>
                </a>
            </div>
            @empty
            <div class="col-12 text-center text-muted py-5">Belum ada artikel terbit.</div>
            @endforelse
        </div>
        @if ($artikels->hasPages())
            <div class="mt-4">{{ $artikels->links() }}</div>
        @endif
    </div>
</div>
@endsection
