<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\SeoController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SubscriptionPlanController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Install\SetupController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

// ─────────── Install wizard (gated by EnsureInstalled middleware) ───────────
Route::prefix('install')->name('install.')->group(function () {
    Route::get('/',           [SetupController::class, 'welcome'])     ->name('welcome');
    Route::get('/database',   [SetupController::class, 'database'])    ->name('database');
    Route::post('/database',  [SetupController::class, 'saveDatabase'])->name('database.save');
    Route::post('/test-db',   [SetupController::class, 'testConnection'])->middleware('throttle:20,1')->name('database.test');
    Route::get('/site',       [SetupController::class, 'site'])        ->name('site');
    Route::post('/site',      [SetupController::class, 'saveSite'])    ->name('site.save');
    Route::get('/admin',      [SetupController::class, 'admin'])       ->name('admin');
    Route::post('/admin',     [SetupController::class, 'saveAdmin'])   ->name('admin.save');
    Route::get('/finalize',   [SetupController::class, 'finalize'])    ->name('finalize');
    Route::post('/finalize',  [SetupController::class, 'install'])     ->name('finalize.run');
    Route::get('/complete',   [SetupController::class, 'complete'])    ->name('complete');
});

// ─────────── Public ───────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('contact.store');

// SEO files
Route::get('/sitemap.xml', [SitemapController::class, 'index']);
Route::get('/robots.txt',  [SitemapController::class, 'robots']);

// ─────────── Auth ───────────
Route::middleware('guest')->group(function () {
    Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:10,1');
});
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// ─────────── Admin ───────────
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'can:access admin'])
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('posts', PostController::class)->except(['show']);
        Route::resource('categories', CategoryController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('tags', TagController::class)->only(['index', 'store', 'destroy']);
        Route::resource('users', UserController::class);
        Route::resource('seo', SeoController::class)->parameters(['seo' => 'meta'])->except(['show']);

        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');

        Route::get('messages', [MessageController::class, 'index'])->name('messages.index');
        Route::get('messages/{message}', [MessageController::class, 'show'])->name('messages.show');
        Route::delete('messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');

        // Subscription plans
        Route::resource('subscriptions', SubscriptionPlanController::class)->except(['show']);
        Route::post('subscriptions/{subscription}/toggle', [SubscriptionPlanController::class, 'toggleActive'])
            ->name('subscriptions.toggle');
    });

// Static pages LAST so they don't capture admin/login/etc.
Route::get('/{slug}', [HomeController::class, 'staticPage'])
    ->where('slug', 'privacy|terms|refund|security|about')
    ->name('page');
