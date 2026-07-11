<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\JsonResponse;

class AdminDashboardController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'total_campaigns' => Campaign::count(),
            'total_donations' => Donation::count(),
            'pending_donations' => Donation::where('status', 'pending')->count(),
            'completed_donations' => Donation::where('status', 'completed')->count(),
        ]);
    }
}
