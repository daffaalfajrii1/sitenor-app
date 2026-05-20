<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengumumanController extends Controller
{
    use HandlesUploads;

    public function index(Request $request)
    {
        $pengumumans = Pengumuman::query()
            ->when($request->search, fn ($q, $s) => $q->where('title', 'like', "%{$s}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.pengumuman.index', compact('pengumumans'));
    }

    public function create()
    {
        return view('admin.pengumuman.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'file' => ['required', 'file', 'max:10240', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $file = $request->file('file');
        $path = $file->store('pengumumans', 'public');

        Pengumuman::create([
            'title' => $validated['title'],
            'slug' => $this->uniqueSlug($validated['title'], Pengumuman::class),
            'description' => $validated['description'] ?? null,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'user_id' => Auth::id(),
            'is_published' => $request->boolean('is_published', true),
            'published_at' => $request->boolean('is_published', true) ? now() : null,
        ]);

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil diunggah.');
    }

    public function edit(Pengumuman $pengumuman)
    {
        return view('admin.pengumuman.edit', compact('pengumuman'));
    }

    public function update(Request $request, Pengumuman $pengumuman)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'max:10240', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'is_published' => $request->boolean('is_published'),
        ];

        if ($pengumuman->title !== $validated['title']) {
            $data['slug'] = $this->uniqueSlug($validated['title'], Pengumuman::class, [], $pengumuman->id);
        }

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($pengumuman->file_path);
            $file = $request->file('file');
            $data['file_path'] = $file->store('pengumumans', 'public');
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_size'] = $file->getSize();
        }

        if ($data['is_published'] && ! $pengumuman->published_at) {
            $data['published_at'] = now();
        }

        if (! $data['is_published']) {
            $data['published_at'] = null;
        }

        $pengumuman->update($data);

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Pengumuman $pengumuman)
    {
        Storage::disk('public')->delete($pengumuman->file_path);
        $pengumuman->delete();

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil dihapus.');
    }
}
