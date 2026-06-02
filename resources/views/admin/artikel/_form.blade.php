@php
    $i = $item ?? $artikel ?? null;
    $editorUploadUrl = $editorUploadUrl ?? (
        ! empty($hideCaborSelect) && function_exists('cabor_route')
            ? cabor_route('cabor.artikel.editor-upload')
            : route('admin.artikel.editor-upload')
    );
    if (! isset($editorDraftKey)) {
        if (! empty($hideCaborSelect) && ($cabor = function_exists('panel_cabor') ? panel_cabor() : null)) {
            $editorDraftKey = 'sitenor_artikel_draft_cabor_'.$cabor->id.'_'.($i?->id ?? 'new');
        } else {
            $editorDraftKey = 'sitenor_artikel_draft_admin_'.($i?->id ?? 'new');
        }
    }
@endphp
<div class="row g-3">
    <div class="col-md-{{ empty($hideCaborSelect) ? '8' : '12' }}">
        <label class="fw-semibold">Judul *</label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $i?->title) }}" required>
    </div>
    @if (empty($hideCaborSelect))
    <div class="col-md-4">
        <label class="fw-semibold">Cabor</label>
        <select name="cabor_id" class="form-control">
            <option value="">Umum</option>
            @foreach ($cabors ?? [] as $c)
                <option value="{{ $c->id }}" @selected(old('cabor_id', $i?->cabor_id) == $c->id)>{{ $c->name }}</option>
            @endforeach
        </select>
    </div>
    @endif
    <div class="col-12">
        <label class="fw-semibold">Ringkasan</label>
        <input type="text" name="excerpt" class="form-control" maxlength="500" placeholder="Ringkasan singkat untuk daftar artikel" value="{{ old('excerpt', $i?->excerpt) }}">
    </div>
    <div class="col-12">
        <label class="fw-semibold">Konten</label>
        <x-artikel-editor
            :upload-url="$editorUploadUrl"
            :draft-key="$editorDraftKey"
            :content="$i?->content ?? ''"
        />
    </div>
    <div class="col-md-6">
        <label class="fw-semibold">Thumbnail</label>
        <input type="file" name="thumbnail" class="form-control" accept="image/*">
        @if ($i?->thumbnail)
            <img src="{{ Storage::url($i->thumbnail) }}" class="mt-2 rounded" height="72" alt="Thumbnail">
        @endif
    </div>
    <div class="col-md-6 d-flex align-items-end">
        @if ($i?->is_published)
            <span class="badge bg-soft-success text-success">Status: Terbit</span>
        @elseif ($i)
            <span class="badge bg-soft-warning text-warning">Status: Draft</span>
        @else
            <span class="text-muted fs-12">Artikel baru akan disimpan sebagai draft hingga diterbitkan.</span>
        @endif
    </div>
</div>
