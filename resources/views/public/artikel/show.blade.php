@extends('layouts.public.app')
@section('title', $artikel->title)

@section('content')
<div class="sitenor-public-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('public.artikel.index') }}">Artikel</a></li>
                        <li class="breadcrumb-item active">{{ Str::limit($artikel->title, 40) }}</li>
                    </ol>
                </nav>
                <article class="sitenor-public-card p-4 p-lg-5">
                    <div class="text-muted fs-12 mb-2">
                        {{ $artikel->cabor?->name ?? 'Umum' }}
                        · {{ $artikel->published_at?->format('d F Y') }}
                        @if ($artikel->user) · {{ $artikel->user->name }} @endif
                    </div>
                    <h1 class="fw-bold mb-3">{{ $artikel->title }}</h1>
                    @if ($artikel->excerpt)
                        <p class="lead text-muted">{{ $artikel->excerpt }}</p>
                    @endif
                    <div class="sitenor-artikel-content">
                        {!! $artikel->content !!}
                    </div>
                </article>
                @if ($related->isNotEmpty())
                <div class="mt-4">
                    <h2 class="h6 fw-bold mb-3">Artikel terkait</h2>
                    <div class="row g-2">
                        @foreach ($related as $r)
                        <div class="col-md-4">
                            <a href="{{ route('public.artikel.show', $r) }}" class="sitenor-artikel-card">
                                <div class="sitenor-artikel-card__body py-3">
                                    <div class="sitenor-artikel-card__title fs-14">{{ $r->title }}</div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
