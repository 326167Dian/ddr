<?php

use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HistoryController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\StructureController;
use App\Http\Controllers\FrontendController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontendController::class, 'home'])->name('home');
Route::get('/struktur-organisasi', [FrontendController::class, 'structure'])->name('structure');
Route::get('/sejarah-organisasi', [FrontendController::class, 'history'])->name('history');
Route::get('/kegiatan-organisasi', [FrontendController::class, 'activities'])->name('activities');
Route::get('/kegiatan-organisasi/{activity}', [FrontendController::class, 'activityDetail'])->name('activities.show');
Route::get('/artikel', [FrontendController::class, 'articles'])->name('articles');
Route::get('/artikel/{slug}', [FrontendController::class, 'articleDetail'])->name('articles.show');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::put('/dashboard/hero', [DashboardController::class, 'updateHero'])->name('dashboard.hero.update');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

        Route::resource('/structures', StructureController::class)->except('show')->names('structures');
        Route::post('/structures/reorder', [StructureController::class, 'reorder'])->name('structures.reorder');
        Route::resource('/histories', HistoryController::class)->except('show')->names('histories');
        Route::post('/histories/reorder', [HistoryController::class, 'reorder'])->name('histories.reorder');
        Route::resource('/activities', ActivityController::class)->except('show')->names('activities');
        Route::post('/activities/reorder', [ActivityController::class, 'reorder'])->name('activities.reorder');
        Route::post('/activities/{activity}/gallery/remove', [ActivityController::class, 'removeGalleryImage'])->name('activities.gallery.remove');
        Route::resource('/articles', ArticleController::class)->except('show')->names('articles');
        Route::post('/articles/reorder', [ArticleController::class, 'reorder'])->name('articles.reorder');
    });
});
