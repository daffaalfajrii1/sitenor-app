@extends('layouts.public.app')
@section('title', 'Data Wasit & Juri')

@section('content')
<div class="sitenor-public-page">
    <div class="container">
        <div class="sitenor-public-page-header">
            <h1>Data Wasit & Juri</h1>
            <p>Wasit dan juri terdaftar — klik <strong>Lihat Detail</strong> untuk profil lengkap.</p>
        </div>

        @include('public.partials.data-filter', ['cabors' => $cabors, 'searchPlaceholder' => 'Cari nama...'])

        <section class="mb-5">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                <h2 class="sitenor-section-title mb-0">
                    <span class="sitenor-section-title__dot sitenor-section-title__dot--danger"></span>
                    Wasit
                    <span class="badge bg-danger-subtle text-danger ms-1">{{ $wasits->total() }}</span>
                </h2>
            </div>
            <div class="row g-4">
                @forelse ($wasits as $wasit)
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <x-public-person-card
                            :name="$wasit->name"
                            :photo-url="$wasit->photoUrl()"
                            :initial="$wasit->photoInitial()"
                            :cabor="$wasit->cabor?->name"
                            badge="Wasit"
                            :meta="[
                                ['icon' => 'bi bi-trophy', 'label' => $wasit->level_label],
                            ]"
                            :modal-url="route('public.wasit.show', $wasit)"
                            action-label="Lihat Detail"
                        />
                    </div>
                @empty
                    <div class="col-12">
                        <div class="sitenor-public-empty sitenor-public-empty--sm">
                            <p class="mb-0">Tidak ada data wasit.</p>
                        </div>
                    </div>
                @endforelse
            </div>
            @include('public.partials.pagination', ['paginator' => $wasits])
        </section>

        <section>
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                <h2 class="sitenor-section-title mb-0">
                    <span class="sitenor-section-title__dot sitenor-section-title__dot--primary"></span>
                    Juri
                    <span class="badge bg-primary-subtle text-primary ms-1">{{ $juris->total() }}</span>
                </h2>
            </div>
            <div class="row g-4">
                @forelse ($juris as $juri)
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <x-public-person-card
                            :name="$juri->name"
                            :photo-url="$juri->photoUrl()"
                            :initial="$juri->photoInitial()"
                            :cabor="$juri->cabor?->name"
                            badge="Juri"
                            :meta="[
                                ['icon' => 'bi bi-trophy', 'label' => $juri->level_label],
                            ]"
                            :modal-url="route('public.juri.show', $juri)"
                            action-label="Lihat Detail"
                        />
                    </div>
                @empty
                    <div class="col-12">
                        <div class="sitenor-public-empty sitenor-public-empty--sm">
                            <p class="mb-0">Tidak ada data juri.</p>
                        </div>
                    </div>
                @endforelse
            </div>
            @include('public.partials.pagination', ['paginator' => $juris])
        </section>
    </div>
</div>

@include('public.partials.person-modal')
@endsection

@push('scripts')
    <script src="{{ asset('js/public-person-modal.js') }}"></script>
@endpush
