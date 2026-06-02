@props([
    'showCabor' => true,
    'showSearch' => true,
    'showGender' => false,
    'showStatus' => true,
    'showPublished' => false,
    'showAtlet' => false,
    'searchPlaceholder' => 'Cari nama...',
    'cabors' => [],
    'atletOptions' => [],
])

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            @if($showCabor && count($cabors))
            <div class="col-md-3">
                <label class="form-label fs-12 mb-1">Cabor</label>
                <select name="cabor_id" class="form-select">
                    <option value="">Semua cabor</option>
                    @foreach($cabors as $c)
                        <option value="{{ $c->id }}" @selected(request('cabor_id') == $c->id)>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            @if($showSearch)
            <div class="col-md-3">
                <label class="form-label fs-12 mb-1">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="{{ $searchPlaceholder }}" value="{{ request('search') }}">
            </div>
            @endif
            @if($showGender)
            <div class="col-md-2">
                <label class="form-label fs-12 mb-1">Jenis kelamin</label>
                <select name="gender" class="form-select">
                    <option value="">Semua</option>
                    <option value="laki-laki" @selected(request('gender') === 'laki-laki')>Laki-laki</option>
                    <option value="perempuan" @selected(request('gender') === 'perempuan')>Perempuan</option>
                </select>
            </div>
            @endif
            @if($showStatus)
            <div class="col-md-2">
                <label class="form-label fs-12 mb-1">Status</label>
                <select name="is_active" class="form-select">
                    <option value="">Semua</option>
                    <option value="1" @selected(request('is_active') === '1')>Aktif</option>
                    <option value="0" @selected(request('is_active') === '0')>Nonaktif</option>
                </select>
            </div>
            @endif
            @if($showPublished)
            <div class="col-md-2">
                <label class="form-label fs-12 mb-1">Publikasi</label>
                <select name="is_published" class="form-select">
                    <option value="">Semua</option>
                    <option value="1" @selected(request('is_published') === '1')>Terbit</option>
                    <option value="0" @selected(request('is_published') === '0')>Draft</option>
                </select>
            </div>
            @endif
            @if($showAtlet && count($atletOptions))
            <div class="col-md-3">
                <label class="form-label fs-12 mb-1">Atlet</label>
                <select name="atlet_id" class="form-select sitenor-filter-atlet">
                    <option value="">Semua atlet</option>
                    @foreach($atletOptions as $a)
                        <option value="{{ $a->id }}" @selected(request('atlet_id') == $a->id)>{{ $a->name }} ({{ $a->cabor?->name }})</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="col-md-auto">
                <button type="submit" class="btn btn-light-brand w-100">Filter</button>
            </div>
            @if(request()->hasAny(['cabor_id', 'search', 'gender', 'is_active', 'atlet_id', 'level', 'is_published']))
            <div class="col-md-auto">
                <a href="{{ url()->current() }}" class="btn btn-light w-100">Reset</a>
            </div>
            @endif
        </form>
    </div>
</div>
