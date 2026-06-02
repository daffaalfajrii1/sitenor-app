@props(['cabors' => [], 'levels' => null, 'searchPlaceholder' => 'Cari...'])

<form method="GET" class="sitenor-public-filter">
    <div class="row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-label fs-12 fw-semibold mb-1">Cari</label>
            <input type="search" name="search" class="form-control" value="{{ request('search') }}" placeholder="{{ $searchPlaceholder }}">
        </div>
        @if (count($cabors))
        <div class="col-md-3">
            <label class="form-label fs-12 fw-semibold mb-1">Cabang Olahraga</label>
            <select name="cabor_id" class="form-select">
                <option value="">Semua cabor</option>
                @foreach ($cabors as $c)
                    <option value="{{ $c->id }}" @selected(request('cabor_id') == $c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        @endif
        @if ($levels)
        <div class="col-md-3">
            <label class="form-label fs-12 fw-semibold mb-1">Level</label>
            <select name="level" class="form-select">
                <option value="">Semua level</option>
                @foreach ($levels as $key => $label)
                    <option value="{{ $key }}" @selected(request('level') === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div class="col-md-2">
            <button type="submit" class="btn btn-danger w-100">Filter</button>
        </div>
    </div>
</form>
