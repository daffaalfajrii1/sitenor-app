@php $i = $item ?? $pengumuman ?? null; @endphp
<div class="row g-3">
    <div class="col-12">
        <label class="fw-semibold">Judul *</label>
        <input name="title" class="form-control" value="{{ old('title', $i?->title) }}" required>
    </div>
    <div class="col-12">
        <label class="fw-semibold">Deskripsi</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $i?->description) }}</textarea>
    </div>
    <div class="col-12">
        <label class="fw-semibold">File {{ $i ? '(kosongkan jika tidak ganti)' : '*' }}</label>
        <input type="file" name="file" class="form-control" {{ $i ? '' : 'required' }}>
    </div>
    <div class="col-12">
        <div class="form-check">
            <input type="checkbox" name="is_published" value="1" class="form-check-input" id="pengumuman-published" @checked(old('is_published', $i?->is_published ?? true))>
            <label class="form-check-label" for="pengumuman-published">Terbitkan</label>
        </div>
    </div>
</div>
