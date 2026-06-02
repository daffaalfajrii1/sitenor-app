<?php

namespace App\Http\Controllers\Cabor;

use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends AdminProfileController
{
    use HandlesUploads;

    public function complete(Request $request): View|RedirectResponse
    {
        $user = $request->user()->load('cabor');

        if (! $user->needsProfileCompletion()) {
            return redirect(cabor_route('cabor.dashboard'));
        }

        return view('cabor.profile.complete', compact('user'));
    }

    public function storeComplete(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user->needsProfileCompletion()) {
            return redirect(cabor_route('cabor.dashboard'));
        }

        $validated = $this->validateProfile($request, $user);

        $this->fillProfile($user, $validated, $request);

        $user->markProfileCompleted();

        return redirect(cabor_route('cabor.dashboard'))->with('swal', [
            'icon' => 'success',
            'title' => 'Profil disimpan',
            'confirmButtonText' => 'OK',
        ]);
    }

    public function skipComplete(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user->needsProfileCompletion()) {
            return redirect(cabor_route('cabor.dashboard'));
        }

        $user->markProfileCompleted();

        return redirect(cabor_route('cabor.dashboard'));
    }

    public function edit(Request $request): View
    {
        return view('cabor.profile.edit', [
            'user' => $request->user()->load('cabor'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $this->validateProfile($request, $user);

        $this->fillProfile($user, $validated, $request);

        return back()->with('swal', [
            'icon' => 'success',
            'title' => 'Profil diperbarui',
            'text' => 'Perubahan profil berhasil disimpan.',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        parent::updatePassword($request);

        return back()->with('swal', [
            'icon' => 'success',
            'title' => 'Password diubah',
            'text' => 'Password berhasil diperbarui.',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }

    private function validateProfile(Request $request, $user, bool $requirePhone = false, bool $requireAvatar = false): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => [$requirePhone ? 'required' : 'nullable', 'string', 'max:30'],
            'instagram' => ['nullable', 'string', 'max:255'],
            'facebook' => ['nullable', 'string', 'max:255'],
            'youtube' => ['nullable', 'string', 'max:255'],
            'tiktok' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'avatar' => [
                $requireAvatar ? 'required' : 'nullable',
                'image',
                'max:2048',
            ],
            'remove_avatar' => ['nullable', 'boolean'],
        ]);
    }

    private function fillProfile($user, array $validated, Request $request): void
    {
        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'instagram' => $validated['instagram'] ?? null,
            'facebook' => $validated['facebook'] ?? null,
            'youtube' => $validated['youtube'] ?? null,
            'tiktok' => $validated['tiktok'] ?? null,
            'bio' => $validated['bio'] ?? null,
        ]);

        if ($request->boolean('remove_avatar') && $user->avatar) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            $user->avatar = null;
        }

        if ($request->hasFile('avatar')) {
            $user->avatar = $this->storeUpload($request->file('avatar'), 'avatars', $user->avatar);
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
    }
}
