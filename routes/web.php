<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminDonorController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\PublicHomeController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/home', [PublicHomeController::class, 'index']);

Route::prefix('admin')->group(function () {
    Route::post('login', [AdminAuthController::class, 'login']);
    Route::post('logout', [AdminAuthController::class, 'logout']);
    Route::get('me', [AdminAuthController::class, 'me']);

    Route::middleware('admin')->group(function () {
        Route::get('dashboard', [AdminDashboardController::class, 'index']);
        Route::resource('banners', BannerController::class)->except(['create', 'edit']);
        Route::resource('campaigns', CampaignController::class)->except(['create', 'edit']);

        Route::get('donations', [DonationController::class, 'index']);
        Route::get('donations/{id}', [DonationController::class, 'show']);
        Route::patch('donations/{id}/approve', [DonationController::class, 'approve']);
        Route::patch('donations/{id}/reject', [DonationController::class, 'reject']);
        Route::delete('donations/{id}', [DonationController::class, 'destroy']);

        Route::get('settings', [SettingController::class, 'index']);
        Route::put('settings', [SettingController::class, 'update']);

        Route::get('donors', [AdminDonorController::class, 'index']);
    });
});

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
