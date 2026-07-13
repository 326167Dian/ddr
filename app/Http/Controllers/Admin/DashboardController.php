<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrganizationActivity;
use App\Models\OrganizationArticle;
use App\Models\OrganizationHistory;
use App\Models\OrganizationProfile;
use App\Models\OrganizationStructure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $profile = OrganizationProfile::query()->firstOrCreate(
            ['slug' => 'dewan-dakwah-risalah'],
            ['name' => 'DEWAN DAKWAH RISALAH']
        );

        $totalActivities = OrganizationActivity::query()->count();
        $totalArticles = OrganizationArticle::query()->count();

        return view('admin.dashboard.index', [
            'profile' => $profile,
            'totalStructures' => OrganizationStructure::query()->count(),
            'activeStructures' => OrganizationStructure::query()->where('is_active', true)->count(),
            'totalHistories' => OrganizationHistory::query()->count(),
            'totalActivities' => $totalActivities,
            'publishedActivities' => OrganizationActivity::query()->where('is_published', true)->count(),
            'totalArticles' => $totalArticles,
            'publishedArticles' => OrganizationArticle::query()->where('is_published', true)->count(),
            'recentActivities' => OrganizationActivity::query()->latest('activity_date')->take(4)->get(),
            'recentArticles' => OrganizationArticle::query()->latest('published_at')->take(4)->get(),
        ]);
    }

    public function updateHero(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_subtitle' => ['nullable', 'string', 'max:255'],
            'hero_file' => ['nullable', 'image', 'max:1024'],
        ], [
            'hero_file.max' => 'Ukuran gambar maksimal 1 MB.',
        ]);

        $profile = OrganizationProfile::query()->firstOrCreate(
            ['slug' => 'dewan-dakwah-risalah'],
            ['name' => 'DEWAN DAKWAH RISALAH']
        );

        if ($request->hasFile('hero_file')) {
            $heroFile = $request->file('hero_file');
            $heroName = 'halamandepan_' . time() . '.' . $heroFile->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('mobilekit/img', $heroFile, $heroName);
            $data['hero_image'] = 'mobilekit/img/' . $heroName;
        }

        unset($data['hero_file']);

        $profile->update($data);

        return redirect()->route('admin.dashboard')->with('status', 'Pengaturan Hero Dashboard berhasil diperbarui.');
    }
}
