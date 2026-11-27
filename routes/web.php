<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\XdrController;
use Illuminate\Support\Facades\Route;

// Public profile page
Route::get('/', [PublicController::class, 'home'])->name('home');

// Images (served from the database; no storage symlink needed)
Route::get('/media/{id}', [MediaController::class, 'show'])->whereNumber('id')->name('media');
Route::get('/file/{path}', [MediaController::class, 'legacy'])->where('path', 'uploads/[A-Za-z0-9._-]+')->name('legacy-file');

// SEO: robots.txt and sitemap.xml (delete the default public/robots.txt so this one is used)
Route::get('/robots.txt', function () {
    return response("User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /login\n\nSitemap: ".url('/sitemap.xml')."\n", 200, ['Content-Type' => 'text/plain']);
});
Route::get('/sitemap.xml', function () {
    $last = optional(\App\Models\Profile::first())->updated_at ?? now();
    $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n"
         .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'
         .'<url><loc>'.e(url('/')).'</loc><lastmod>'.$last->toAtomString().'</lastmod><changefreq>weekly</changefreq><priority>1.0</priority></url>'
         .'</urlset>';

    return response($xml, 200, ['Content-Type' => 'application/xml']);
});

// Google Search Console ownership verification (HTML file method)
Route::get('/google67ba2e4f33e282d3.html', function () {
    return response('google-site-verification: google67ba2e4f33e282d3.html', 200, ['Content-Type' => 'text/html']);
});

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Admin panel (login required)
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::put('/profile', [AdminController::class, 'updateProfile'])->name('profile');
    Route::put('/password', [AdminController::class, 'updatePassword'])->name('password');
    Route::get('/xdr', [XdrController::class, 'dashboard'])->name('xdr');
    Route::get('/xdr/requests', [XdrController::class, 'requests'])->name('xdr.requests');
    Route::get('/xdr/benefits', [XdrController::class, 'benefits'])->name('xdr.benefits');
    Route::get('/xdr/audit-log', [XdrController::class, 'auditLog'])->name('xdr.audit-log');

    $types = 'social|education|documentary|gallery|slide';
    Route::post('/{type}', [AdminController::class, 'store'])->where('type', $types)->name('store');
    Route::delete('/{type}/{id}', [AdminController::class, 'destroy'])->where('type', $types)->name('destroy');
});
