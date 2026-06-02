@props([
    'module',
    'createRoute' => null,
    'createLabel' => 'Tambah',
    'templateUrl' => null,
    'importUrl' => null,
])

@php
    $templateUrl = $templateUrl ?? route('admin.excel.template', $module);
    $importUrl = $importUrl ?? route('admin.excel.import', $module);
@endphp

<div class="d-flex flex-wrap gap-2">
    @if($createRoute)
        <a href="{{ $createRoute }}" class="btn btn-primary">
            <i class="feather-plus me-2"></i>{{ $createLabel }}
        </a>
    @endif
    <a href="{{ $templateUrl }}" class="btn btn-light-brand">
        <i class="feather-download me-2"></i>Template Excel
    </a>
    <button type="button" class="btn btn-light-brand" data-bs-toggle="modal" data-bs-target="#excelImportModal-{{ $module }}">
        <i class="feather-upload me-2"></i>Impor Excel
    </button>
</div>

<div class="modal fade" id="excelImportModal-{{ $module }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ $importUrl }}" method="POST" enctype="multipart/form-data">                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Impor dari Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted fs-12 mb-3">Unduh template terlebih dahulu, isi data, lalu unggah file <strong>.xlsx</strong>.</p>
                    <label class="form-label fw-semibold">File Excel</label>
                    <input type="file" name="file" class="form-control" accept=".xlsx,.xls" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Impor</button>
                </div>
            </form>
        </div>
    </div>
</div>
