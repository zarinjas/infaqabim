<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminDonorController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\PublicHomeController;
use App\Http\Controllers\PublicSettingController;
use App\Http\Controllers\SenangPayCallbackController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome', ['og' => []]);
});

Route::get('/api/home', [PublicHomeController::class, 'index']);
Route::get('/api/campaigns/{id}', [PublicHomeController::class, 'show']);
Route::get('/api/settings', [PublicSettingController::class, 'index']);

Route::post('/api/donations', [DonationController::class, 'store'])->middleware('throttle:10,1');
Route::get('/api/donations/status/{reference}', [DonationController::class, 'status']);

Route::post('/api/senangpay/callback', [SenangPayCallbackController::class, 'callback']);
Route::get('/api/senangpay/return', [SenangPayCallbackController::class, 'return']);

Route::prefix('admin')->group(function () {
    Route::post('login', [AdminAuthController::class, 'login'])->middleware('throttle:5,1');
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
        Route::post('settings', [SettingController::class, 'update']); // Use POST to handle FormData for file uploads

        Route::get('donors', [AdminDonorController::class, 'index']);
    });
});

Route::get('/{any}', function ($any) {
    $og = [];
    
    // Inject OG tags for specific campaign routes
    if (str_starts_with($any, 'campaigns/')) {
        $segments = explode('/', $any);
        if (isset($segments[1]) && is_numeric($segments[1])) {
            $campaign = \App\Models\Campaign::find($segments[1]);
            if ($campaign) {
                $og = [
                    'title' => $campaign->title,
                    'description' => \Illuminate\Support\Str::limit(strip_tags($campaign->description), 150),
                    'type' => 'article',
                ];
                if ($campaign->image) {
                    $og['image'] = asset('storage/' . $campaign->image);
                }
            }
        }
    }

    return view('welcome', compact('og'));
})->where('any', '.*');
