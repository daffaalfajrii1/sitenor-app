<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class SuperAdminController extends Controller
{
    use HandlesUploads;

    public function index(Request $request): View
    {
        $superAdmins = User::query()
            ->role(User::ROLE_SUPER_ADMIN)
            ->when($request->search, fn ($q, $s) => $q->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('email', 'like', "%{$s}%");
            }))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.super-admin.index', [
            'superAdmins' => $superAdmins,
        ]);
    }

    public function create(): View
    {
        return view('admin.super-admin.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        Role::findOrCreate(User::ROLE_SUPER_ADMIN, 'web');

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'avatar' => $this->storeUpload($request->file('avatar'), 'avatars'),
            'email_verified_at' => now(),
            'profile_completed_at' => now(),
            'registered_by_admin' => true,
        ]);

        $user->assignRole(User::ROLE_SUPER_ADMIN);

        return redirect()->route('admin.super-admin.index')
            ->with('success', 'Super admin berhasil ditambahkan.');
    }

    public function edit(User $superAdmin): View
    {
        $this->ensureSuperAdmin($superAdmin);

        return view('admin.super-admin.edit', [
            'superAdmin' => $superAdmin,
        ]);
    }

    public function update(Request $request, User $superAdmin): RedirectResponse
    {
        $this->ensureSuperAdmin($superAdmin);

        $validated = $this->validated($request, $superAdmin);

        $superAdmin->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (! empty($validated['password'])) {
            $superAdmin->password = $validated['password'];
        }

        if ($request->boolean('remove_avatar') && $superAdmin->avatar) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($superAdmin->avatar);
            $superAdmin->avatar = null;
        }

        if ($request->hasFile('avatar')) {
            $superAdmin->avatar = $this->storeUpload($request->file('avatar'), 'avatars', $superAdmin->avatar);
        }

        $superAdmin->save();

        return redirect()->route('admin.super-admin.index')
            ->with('success', 'Super admin berhasil diperbarui.');
    }

    public function destroy(User $superAdmin): RedirectResponse
    {
        $this->ensureSuperAdmin($superAdmin);

        if ($superAdmin->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun yang sedang digunakan.');
        }

        $count = User::role(User::ROLE_SUPER_ADMIN)->count();
        if ($count <= 1) {
            return back()->with('error', 'Minimal harus ada satu super admin.');
        }

        if ($superAdmin->avatar) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($superAdmin->avatar);
        }

        $superAdmin->delete();

        return redirect()->route('admin.super-admin.index')
            ->with('success', 'Super admin berhasil dihapus.');
    }

    private function ensureSuperAdmin(User $user): void
    {
        if (! $user->hasRole(User::ROLE_SUPER_ADMIN)) {
            abort(404);
        }
    }

    private function validated(Request $request, ?User $user = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user?->id)],
            'password' => [$user ? 'nullable' : 'required', 'confirmed', Password::defaults()],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'remove_avatar' => ['nullable', 'boolean'],
        ]);
    }
}
