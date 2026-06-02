<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteSettingController extends Controller
{
    use HandlesUploads;

    public function edit(): View
    {
        return view('admin.settings.edit', [
            'settings' => SiteSetting::current(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $settings = SiteSetting::current();

        $validated = $request->validate([
            'app_name' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'visi' => ['nullable', 'string'],
            'misi' => ['nullable', 'string'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
            'instagram' => ['nullable', 'string', 'max:255'],
            'facebook' => ['nullable', 'string', 'max:255'],
            'youtube' => ['nullable', 'string', 'max:255'],
            'footer_text' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'favicon' => ['nullable', 'image', 'max:1024', 'mimes:png,ico,jpg,jpeg'],
            'remove_logo' => ['nullable', 'boolean'],
            'remove_favicon' => ['nullable', 'boolean'],
        ]);

        if ($request->boolean('remove_logo') && $settings->logo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($settings->logo);
            $validated['logo'] = null;
        } elseif ($request->hasFile('logo')) {
            $validated['logo'] = $this->storeUpload($request->file('logo'), 'settings', $settings->logo);
        } else {
            unset($validated['logo']);
        }

        if ($request->boolean('remove_favicon') && $settings->favicon) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($settings->favicon);
            $validated['favicon'] = null;
        } elseif ($request->hasFile('favicon')) {
            $validated['favicon'] = $this->storeUpload($request->file('favicon'), 'settings', $settings->favicon);
        } else {
            unset($validated['favicon']);
        }

        unset($validated['remove_logo'], $validated['remove_favicon']);

        $settings->update($validated);

        return redirect()
            ->route('admin.settings.edit')
            ->with('success', 'Pengaturan website berhasil disimpan.');
    }
}
