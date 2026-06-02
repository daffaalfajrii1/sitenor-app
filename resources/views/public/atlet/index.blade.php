@extends('layouts.public.app')
@section('title', 'Data Atlet')

@section('content')
<div class="sitenor-public-page">
    <div class="container">
        <div class="sitenor-public-page-header">
            <h1>Data Atlet</h1>
            <p>Daftar atlet aktif Kabupaten Rejang Lebong — klik detail untuk melihat prestasi.</p>
        </div>

        @include('public.partials.data-filter', ['cabors' => $cabors, 'searchPlaceholder' => 'Cari nama atlet...'])

        <div class="row g-4">
            @forelse ($atlets as $atlet)
                <div class="col-sm-6 col-lg-4 col-xl-3">
                    <x-public-person-card
                        :name="$atlet->name"
                        :photo-url="$atlet->photoUrl()"
                        :initial="$atlet->photoInitial()"
                        :cabor="$atlet->cabor?->name"
                        :badge="$atlet->prestasis_count.' Prestasi'"
                        :meta="array_filter([
                            ['icon' => 'bi bi-calendar3', 'label' => $atlet->age_label],
                            ['icon' => 'bi bi-person', 'label' => $atlet->gender ? ucfirst($atlet->gender) : null],
                        ])"
                        :href="route('public.atlet.show', $atlet)"
                        action-label="Lihat Prestasi"
                    />
                </div>
            @empty
                <div class="col-12">
                    <div class="sitenor-public-empty">
                        <i class="bi bi-people"></i>
                        <p>Belum ada data atlet.</p>
                    </div>
                </div>
            @endforelse
        </div>

        @include('public.partials.pagination', ['paginator' => $atlets])
    </div>
</div>
@endsection
