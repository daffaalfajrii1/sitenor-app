@extends('layouts.public.app')
@section('title', 'Data Pelatih')

@section('content')
<div class="sitenor-public-page">
    <div class="container">
        <div class="sitenor-public-page-header">
            <h1>Data Pelatih</h1>
            <p>Daftar pelatih aktif per cabang olahraga Rejang Lebong.</p>
        </div>

        @include('public.partials.data-filter', ['cabors' => $cabors, 'searchPlaceholder' => 'Cari nama pelatih...'])

        <div class="row g-4">
            @forelse ($pelatihs as $pelatih)
                <div class="col-sm-6 col-lg-4 col-xl-3">
                    <x-public-person-card
                        :name="$pelatih->name"
                        :photo-url="$pelatih->photoUrl()"
                        :initial="$pelatih->photoInitial()"
                        :cabor="$pelatih->cabor?->name"
                        :meta="[
                            ['icon' => 'bi bi-trophy', 'label' => $pelatih->level_label],
                            ['icon' => 'bi bi-credit-card-2', 'label' => $pelatih->license_number ? 'Lisensi: '.$pelatih->license_number : '—'],
                        ]"
                        :modal-url="route('public.pelatih.show', $pelatih)"
                        action-label="Lihat Detail"
                    />
                </div>
            @empty
                <div class="col-12">
                    <div class="sitenor-public-empty">
                        <i class="bi bi-people"></i>
                        <p>Belum ada data pelatih.</p>
                    </div>
                </div>
            @endforelse
        </div>

        @include('public.partials.pagination', ['paginator' => $pelatihs])
    </div>
</div>

@include('public.partials.person-modal')
@endsection

@push('scripts')
    <script src="{{ asset('js/public-person-modal.js') }}"></script>
@endpush
