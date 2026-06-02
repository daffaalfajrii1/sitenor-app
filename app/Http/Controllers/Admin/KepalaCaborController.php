<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Models\Cabor;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class KepalaCaborController extends Controller
{
    use HandlesUploads;

    public function index(Request $request): View
    {
        $kepalaCabors = User::query()
            ->role(User::ROLE_ADMIN_CABOR)
            ->with('cabor')
            ->when($request->cabor_id, fn ($q) => $q->where('cabor_id', $request->cabor_id))
            ->when($request->search, fn ($q, $s) => $q->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('email', 'like', "%{$s}%");
            }))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.kepala-cabor.index', [
            'kepalaCabors' => $kepalaCabors,
            'cabors' => Cabor::orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.kepala-cabor.create', [
            'cabors' => Cabor::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'cabor_id' => $validated['cabor_id'],
            'phone' => $validated['phone'] ?? null,
            'instagram' => $validated['instagram'] ?? null,
            'facebook' => $validated['facebook'] ?? null,
            'youtube' => $validated['youtube'] ?? null,
            'tiktok' => $validated['tiktok'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'avatar' => $this->storeUpload($request->file('avatar'), 'avatars'),
            'registered_by_admin' => true,
            'profile_completed_at' => now(),
        ]);

        $user->assignRole(User::ROLE_ADMIN_CABOR);

        return redirect()->route('admin.kepala-cabor.index')->with('success', 'Kepala cabor berhasil ditambahkan.');
    }

    public function edit(User $kepalaCabor): View
    {
        $this->ensureKepalaCabor($kepalaCabor);

        return view('admin.kepala-cabor.edit', [
            'kepalaCabor' => $kepalaCabor->load('cabor'),
            'cabors' => Cabor::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, User $kepalaCabor): RedirectResponse
    {
        $this->ensureKepalaCabor($kepalaCabor);

        $validated = $this->validated($request, $kepalaCabor);

        $kepalaCabor->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'cabor_id' => $validated['cabor_id'],
            'phone' => $validated['phone'] ?? null,
            'instagram' => $validated['instagram'] ?? null,
            'facebook' => $validated['facebook'] ?? null,
            'youtube' => $validated['youtube'] ?? null,
            'tiktok' => $validated['tiktok'] ?? null,
            'bio' => $validated['bio'] ?? null,
        ]);

        if (! empty($validated['password'])) {
            $kepalaCabor->password = $validated['password'];
        }

        if ($request->boolean('remove_avatar') && $kepalaCabor->avatar) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($kepalaCabor->avatar);
            $kepalaCabor->avatar = null;
        }

        if ($request->hasFile('avatar')) {
            $kepalaCabor->avatar = $this->storeUpload($request->file('avatar'), 'avatars', $kepalaCabor->avatar);
        }

        $kepalaCabor->save();

        return redirect()->route('admin.kepala-cabor.index')->with('success', 'Kepala cabor berhasil diperbarui.');
    }

    public function destroy(User $kepalaCabor): RedirectResponse
    {
        $this->ensureKepalaCabor($kepalaCabor);

        if ($kepalaCabor->avatar) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($kepalaCabor->avatar);
        }

        $kepalaCabor->delete();

        return redirect()->route('admin.kepala-cabor.index')->with('success', 'Kepala cabor berhasil dihapus.');
    }

    private function ensureKepalaCabor(User $user): void
    {
        if (! $user->hasRole(User::ROLE_ADMIN_CABOR)) {
            abort(404);
        }
    }

    private function validated(Request $request, ?User $user = null): array
    {
        $caborId = $request->input('cabor_id');

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user?->id)],
            'password' => [$user ? 'nullable' : 'required', 'confirmed', Password::defaults()],
            'cabor_id' => [
                'required',
                'exists:cabors,id',
                function ($attribute, $value, $fail) use ($user) {
                    $exists = User::query()
                        ->where('cabor_id', $value)
                        ->role(User::ROLE_ADMIN_CABOR)
                        ->when($user, fn ($q) => $q->where('id', '!=', $user->id))
                        ->exists();

                    if ($exists) {
                        $fail('Cabang olahraga ini sudah memiliki kepala cabor.');
                    }
                },
            ],
            'phone' => ['nullable', 'string', 'max:30'],
            'instagram' => ['nullable', 'string', 'max:255'],
            'facebook' => ['nullable', 'string', 'max:255'],
            'youtube' => ['nullable', 'string', 'max:255'],
            'tiktok' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'remove_avatar' => ['nullable', 'boolean'],
        ]);
    }
}
