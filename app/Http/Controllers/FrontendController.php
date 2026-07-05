<?php

namespace App\Http\Controllers;

use App\Models\OrganizationActivity;
use App\Models\OrganizationArticle;
use App\Models\OrganizationHistory;
use App\Models\OrganizationProfile;
use App\Models\OrganizationStructure;
use Illuminate\View\View;

class FrontendController extends Controller
{
    public function home(): View
    {
        $profile = OrganizationProfile::query()->first();
        $activities = OrganizationActivity::query()
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->latest('activity_date')
            ->take(3)
            ->get();
        $articles = OrganizationArticle::query()
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('frontend.home', compact('profile', 'activities', 'articles'));
    }

    public function structure(): View
    {
        $profile = OrganizationProfile::query()->first();
        $structures = OrganizationStructure::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('frontend.structure', compact('profile', 'structures'));
    }

    public function history(): View
    {
        $profile = OrganizationProfile::query()->first();
        $histories = OrganizationHistory::query()
            ->orderBy('sort_order')
            ->get();

        return view('frontend.history', compact('profile', 'histories'));
    }

    public function activities(): View
    {
        $profile = OrganizationProfile::query()->first();
        $activities = OrganizationActivity::query()
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->latest('activity_date')
            ->get();

        return view('frontend.activities', compact('profile', 'activities'));
    }

    public function activityDetail(OrganizationActivity $activity): View
    {
        abort_if(! $activity->is_published, 404);

        $profile = OrganizationProfile::query()->first();

        return view('frontend.activity-detail', compact('profile', 'activity'));
    }

    public function articles(): View
    {
        $profile = OrganizationProfile::query()->first();
        $articles = OrganizationArticle::query()
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->latest('published_at')
            ->get();

        return view('frontend.articles', compact('profile', 'articles'));
    }

    public function articleDetail(string $slug): View
    {
        $profile = OrganizationProfile::query()->first();
        $article = OrganizationArticle::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return view('frontend.article-detail', compact('profile', 'article'));
    }
}
