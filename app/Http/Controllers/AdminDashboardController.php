<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\JsonResponse;

class AdminDashboardController extends Controller
{
    public function index(): JsonResponse
    {
        $recentDonations = Donation::with('campaign')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($d) => [
                'id' => $d->id,
                'donor_name' => $d->donor_name,
                'amount' => (float) $d->amount,
                'status' => $d->status,
                'campaign' => $d->campaign?->title,
                'created_at' => $d->created_at->diffForHumans(),
            ]);

        return response()->json([
            'total_campaigns' => Campaign::count(),
            'total_donations' => Donation::count(),
            'pending_donations' => Donation::where('status', 'pending')->count(),
            'completed_donations' => Donation::where('status', 'completed')->count(),
            'total_collected' => (float) Donation::where('status', 'completed')->sum('amount'),
            'recent_donations' => $recentDonations,
        ]);
    }
}
