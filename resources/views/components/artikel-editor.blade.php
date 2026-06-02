@props([
    'uploadUrl',
    'draftKey',
    'content' => '',
])

@php
    $tinymceBase = 'https://cdn.jsdelivr.net/npm/tinymce@7.6.0';
@endphp

@push('styles')
<link rel="stylesheet" href="{{ $tinymceBase }}/skins/ui/oxide/skin.min.css">
<style>
    .sitenor-artikel-editor .tox-tinymce {
        border-radius: 8px;
        border-color: var(--bs-border-color, #e5e7eb);
    }
    .sitenor-artikel-editor-status {
        font-size: 0.8125rem;
        color: #64748b;
        min-height: 1.25rem;
    }
    .sitenor-artikel-editor-status.is-saving { color: #d97706; }
    .sitenor-artikel-editor-status.is-saved { color: #059669; }
    .sitenor-artikel-editor-status.is-error { color: #dc2626; }
</style>
@endpush

<div class="sitenor-artikel-editor">
    <textarea
        id="artikel-content"
        name="content"
        class="form-control"
        rows="12"
    >{{ old('content', $content) }}</textarea>
    <div id="artikel-editor-status" class="sitenor-artikel-editor-status mt-2" aria-live="polite"></div>
</div>

@push('scripts')
<script src="{{ $tinymceBase }}/tinymce.min.js"></script>
<script>
(function () {
    const uploadUrl = @json($uploadUrl);
    const draftKey = @json($draftKey);
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const statusEl = document.getElementById('artikel-editor-status');
    const form = document.getElementById('artikel-form');
    const titleInput = form?.querySelector('[name="title"]');
    const excerptInput = form?.querySelector('[name="excerpt"]');

    function setStatus(text, state) {
        if (!statusEl) return;
        statusEl.textContent = text;
        statusEl.classList.remove('is-saving', 'is-saved', 'is-error');
        if (state) statusEl.classList.add(state);
    }

    function readLocalDraft() {
        try {
            return JSON.parse(localStorage.getItem(draftKey) || 'null');
        } catch (e) {
            return null;
        }
    }

    function writeLocalDraft(payload) {
        localStorage.setItem(draftKey, JSON.stringify({
            ...payload,
            savedAt: new Date().toISOString(),
        }));
    }

    function clearLocalDraft() {
        localStorage.removeItem(draftKey);
    }

    function collectDraftPayload(editor) {
        return {
            title: titleInput?.value ?? '',
            excerpt: excerptInput?.value ?? '',
            content: editor.getContent(),
        };
    }

    function applyDraft(payload, editor) {
        if (titleInput && payload.title) titleInput.value = payload.title;
        if (excerptInput && payload.excerpt) excerptInput.value = payload.excerpt;
        if (payload.content) editor.setContent(payload.content);
    }

    async function uploadEditorImage(blobInfo, progress) {
        if (!csrfToken) {
            throw new Error('Token keamanan tidak ditemukan. Muat ulang halaman.');
        }

        const formData = new FormData();
        const filename = blobInfo.filename() || 'gambar.jpg';
        formData.append('file', blobInfo.blob(), filename);
        formData.append('_token', csrfToken);

        const response = await fetch(uploadUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: formData,
            credentials: 'same-origin',
        });

        if (!response.ok) {
            let message = 'Gagal mengunggah gambar (HTTP ' + response.status + ').';
            try {
                const data = await response.json();
                if (data.message) message = data.message;
                if (data.errors?.file?.[0]) message = data.errors.file[0];
            } catch (e) { /* ignore */ }
            throw new Error(message);
        }

        const data = await response.json();
        if (!data.location) {
            throw new Error('Respons server tidak berisi URL gambar.');
        }

        return data.location;
    }

    tinymce.init({
        selector: '#artikel-content',
        base_url: @json($tinymceBase),
        suffix: '.min',
        height: 480,
        menubar: 'file edit view insert format tools table help',
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount', 'autosave'
        ],
        toolbar: 'undo redo | blocks | bold italic underline strikethrough | '
            + 'alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | '
            + 'link image media table | removeformat code fullscreen',
        branding: false,
        promotion: false,
        license_key: 'gpl',
        skin: 'oxide',
        content_style: 'body { font-family: "Plus Jakarta Sans", system-ui, sans-serif; font-size: 15px; line-height: 1.6; } img { max-width: 100%; height: auto; }',
        relative_urls: false,
        remove_script_host: false,
        convert_urls: true,
        automatic_uploads: true,
        paste_data_images: true,
        images_file_types: 'jpeg,jpg,png,gif,webp',
        images_upload_handler: (blobInfo, progress) => {
            setStatus('Mengunggah gambar...', 'is-saving');
            return uploadEditorImage(blobInfo, progress)
                .then((url) => {
                    setStatus('Gambar berhasil dimasukkan.', 'is-saved');
                    return url;
                })
                .catch((err) => {
                    const msg = err?.message || 'Gagal mengunggah gambar.';
                    setStatus(msg, 'is-error');
                    return Promise.reject(msg);
                });
        },
        file_picker_callback: (callback, value, meta) => {
            if (meta.filetype !== 'image') {
                return;
            }
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/jpeg,image/png,image/gif,image/webp';
            input.onchange = async () => {
                const file = input.files?.[0];
                if (!file) return;
                const blobInfo = {
                    blob: () => file,
                    filename: () => file.name,
                };
                try {
                    const url = await uploadEditorImage(blobInfo, () => {});
                    callback(url, { alt: file.name });
                } catch (err) {
                    alert(err?.message || 'Gagal mengunggah gambar.');
                }
            };
            input.click();
        },
        autosave_interval: '30s',
        autosave_retention: '1440m',
        autosave_prefix: 'tinymce-autosave-{path}{query}-',
        autosave_restore_when_empty: false,
        setup: (editor) => {
            editor.on('init', () => {
                const serverSnapshot = JSON.stringify({
                    title: titleInput?.value ?? '',
                    excerpt: excerptInput?.value ?? '',
                    content: editor.getContent(),
                });
                const localDraft = readLocalDraft();

                if (localDraft && localDraft.snapshot !== serverSnapshot) {
                    const savedAt = localDraft.savedAt
                        ? new Date(localDraft.savedAt).toLocaleString('id-ID')
                        : '';
                    if (confirm('Ada draft lokal yang belum disimpan' + (savedAt ? ' (' + savedAt + ')' : '') + '. Pulihkan draft?')) {
                        applyDraft(localDraft, editor);
                        setStatus('Draft lokal dipulihkan.', 'is-saved');
                    } else {
                        clearLocalDraft();
                    }
                }

                let autosaveTimer = null;
                const persistDraft = () => {
                    const payload = collectDraftPayload(editor);
                    writeLocalDraft({
                        ...payload,
                        snapshot: JSON.stringify(payload),
                    });
                    setStatus('Draft tersimpan otomatis di perangkat ini.', 'is-saved');
                };

                editor.on('change input undo redo', () => {
                    setStatus('Menyimpan draft...', 'is-saving');
                    clearTimeout(autosaveTimer);
                    autosaveTimer = setTimeout(persistDraft, 1500);
                });

                if (titleInput) titleInput.addEventListener('input', () => {
                    clearTimeout(autosaveTimer);
                    autosaveTimer = setTimeout(persistDraft, 1500);
                });
                if (excerptInput) excerptInput.addEventListener('input', () => {
                    clearTimeout(autosaveTimer);
                    autosaveTimer = setTimeout(persistDraft, 1500);
                });
            });
        },
    }).then((editors) => {
        const editor = editors[0];
        if (!form) return;

        form.addEventListener('submit', () => {
            tinymce.triggerSave();
            clearLocalDraft();
        });
    }).catch((err) => {
        console.error('TinyMCE gagal dimuat:', err);
        setStatus('Editor gagal dimuat. Periksa koneksi internet atau muat ulang halaman.', 'is-error');
    });
})();
</script>
@endpush
