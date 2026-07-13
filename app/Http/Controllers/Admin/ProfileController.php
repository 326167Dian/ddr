<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrganizationProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $profile = OrganizationProfile::query()->firstOrCreate(
            ['slug' => 'dewan-dakwah-risalah'],
            ['name' => 'DEWAN DAKWAH RISALAH']
        );

        return view('admin.profiles.edit', compact('profile'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_subtitle' => ['nullable', 'string', 'max:255'],
            'hero_image' => ['nullable', 'string', 'max:255'],
            'logo_path' => ['nullable', 'string', 'max:255'],
            'prayer_city_code' => ['nullable', 'string', 'in:solok,padang,jakarta,bandung,yogyakarta,surabaya'],
            'vision' => ['nullable', 'string'],
            'mission' => ['nullable', 'string'],
            'about' => ['nullable', 'string'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string'],
            'logo_file' => ['nullable', 'image', 'max:4096'],
            'hero_file' => ['nullable', 'image', 'max:1024'],
        ]);

        unset($data['logo_file'], $data['hero_file']);

        $profile = OrganizationProfile::query()->firstOrFail();

        if ($request->hasFile('logo_file')) {
            $logoFile = $request->file('logo_file');
            $logoName = 'logo_ddr_' . time() . '.' . $logoFile->getClientOriginalExtension();
            $targetDir = rtrim($_SERVER['DOCUMENT_ROOT'] ?? public_path(), DIRECTORY_SEPARATOR)
                . DIRECTORY_SEPARATOR . 'mobilekit'
                . DIRECTORY_SEPARATOR . 'img';
            if (! is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            $logoFile->move($targetDir, $logoName);
            $data['logo_path'] = 'mobilekit/img/' . $logoName;
        }

        if ($request->hasFile('hero_file')) {
            $heroFile = $request->file('hero_file');
            $heroName = 'halamandepan_' . time() . '.' . $heroFile->getClientOriginalExtension();
            $targetDir = rtrim($_SERVER['DOCUMENT_ROOT'] ?? public_path(), DIRECTORY_SEPARATOR)
                . DIRECTORY_SEPARATOR . 'mobilekit'
                . DIRECTORY_SEPARATOR . 'img';
            if (! is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            $heroFile->move($targetDir, $heroName);
            $data['hero_image'] = 'mobilekit/img/' . $heroName;
        }

        if (! Schema::hasColumn('organization_profiles', 'prayer_city_code')) {
            unset($data['prayer_city_code']);
        }

        $profile->update($data);

        return back()->with('status', 'Profil organisasi berhasil diperbarui.');
    }
}
