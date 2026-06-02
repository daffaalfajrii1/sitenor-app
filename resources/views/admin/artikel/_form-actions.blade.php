@php
    $cancelUrl = $cancelUrl ?? route('admin.artikel.index');
@endphp
<div class="d-flex flex-wrap gap-2 mt-4 pt-3 border-top">
    <button type="submit" name="save_action" value="publish" class="btn btn-primary">
        <i class="feather-send me-1"></i> Terbitkan
    </button>
    <button type="submit" name="save_action" value="draft" class="btn btn-light-brand">
        <i class="feather-save me-1"></i> Simpan draft
    </button>
    <a href="{{ $cancelUrl }}" class="btn btn-light ms-auto">Batal</a>
</div>
<p class="text-muted fs-12 mb-0 mt-2">
  <i class="feather-info me-1"></i>
  Draft disimpan otomatis di browser saat mengetik. Gunakan <strong>Simpan draft</strong> untuk menyimpan ke server tanpa menerbitkan.
</p>
