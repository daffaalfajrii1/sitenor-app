<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cabor;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RegisteredKepalaCaborController extends Controller
{
    public function create(): View
    {
        $occupiedCaborIds = User::query()
            ->role(User::ROLE_ADMIN_CABOR)
            ->whereNotNull('cabor_id')
            ->pluck('cabor_id')
            ->all();

        return view('auth.register', [
            'cabors' => Cabor::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),
            'occupiedCaborIds' => $occupiedCaborIds,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'cabor_id' => [
                'required',
                'exists:cabors,id',
                function ($attribute, $value, $fail) {
                    $exists = User::query()
                        ->where('cabor_id', $value)
                        ->role(User::ROLE_ADMIN_CABOR)
                        ->exists();

                    if ($exists) {
                        $fail('Cabang olahraga ini sudah memiliki kepala cabor. Satu cabor hanya satu kepala cabor.');
                    }
                },
            ],
        ], [
            'email.unique' => 'Email ini sudah terdaftar. Gunakan email lain atau masuk ke akun Anda.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'cabor_id.required' => 'Pilih cabang olahraga.',
            'cabor_id.exists' => 'Cabang olahraga tidak valid.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        Role::findOrCreate(User::ROLE_ADMIN_CABOR, 'web');

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'cabor_id' => $validated['cabor_id'],
            'registered_by_admin' => false,
            'profile_completed_at' => null,
        ]);

        $user->assignRole(User::ROLE_ADMIN_CABOR);

        event(new Registered($user));

        Auth::login($user);

        $request->session()->regenerate();

        return redirect(cabor_route('cabor.dashboard', $user->cabor))
            ->with('success', 'Pendaftaran berhasil. Selamat datang!');
    }
}
