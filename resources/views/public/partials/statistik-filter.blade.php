<form method="GET" action="{{ route('public.statistik') }}" class="sitenor-public-filter sitenor-statistik-filter">
    <div class="row g-2 align-items-end">
        <div class="col-md-8 col-lg-9">
            <label class="form-label fs-12 fw-semibold mb-1" for="statistikSearch">Cari cabang olahraga</label>
            <div class="input-group">
                <span class="input-group-text bg-white text-muted border-end-0">
                    <i class="bi bi-search" aria-hidden="true"></i>
                </span>
                <input
                    type="search"
                    id="statistikSearch"
                    name="search"
                    class="form-control border-start-0 ps-0"
                    value="{{ $search ?? request('search') }}"
                    placeholder="Nama atau kode cabor, mis. Anggar, CAB-2026-001..."
                    autocomplete="off"
                >
            </div>
        </div>
        <div class="col-md-4 col-lg-3 d-flex gap-2">
            <button type="submit" class="btn btn-danger flex-grow-1 fw-semibold">
                <i class="bi bi-search me-1"></i> Cari
            </button>
            @if (filled($search ?? request('search')))
                <a href="{{ route('public.statistik') }}" class="btn btn-outline-secondary" title="Hapus filter">Reset</a>
            @endif
        </div>
    </div>
    @if (filled($search ?? request('search')))
        <p class="sitenor-statistik-filter__hint mb-0 mt-2">
            Menampilkan statistik untuk pencarian <strong>&ldquo;{{ $search ?? request('search') }}&rdquo;</strong>
        </p>
    @endif
</form>
