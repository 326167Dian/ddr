<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrganizationActivity;
use App\Models\OrganizationArticle;
use App\Models\OrganizationHistory;
use App\Models\OrganizationStructure;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalActivities = OrganizationActivity::query()->count();
        $totalArticles = OrganizationArticle::query()->count();

        return view('admin.dashboard.index', [
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
}
