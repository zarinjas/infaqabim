<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Campaign;
use Illuminate\Http\JsonResponse;

class PublicHomeController extends Controller
{
    public function index(): JsonResponse
    {
        $banners = Banner::where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'title', 'subtitle', 'image', 'link']);

        $featured = Campaign::where('is_active', true)
            ->withCount(['donations' => fn ($q) => $q->where('status', 'completed')])
            ->orderBy('collected_amount', 'desc')
            ->first(['id', 'title', 'description', 'target_amount', 'collected_amount', 'image']);

        $campaigns = Campaign::where('is_active', true)
            ->withCount(['donations' => fn ($q) => $q->where('status', 'completed')])
            ->orderBy('created_at', 'desc')
            ->get(['id', 'title', 'description', 'target_amount', 'collected_amount', 'image']);

        return response()->json([
            'banners' => $banners,
            'featured' => $featured,
            'campaigns' => $campaigns,
        ]);
    }

    /**
     * Public campaign detail lookup - only exposes active campaigns with
     * the fields safe for public display.
     */
    public function show($id): JsonResponse
    {
        $campaign = Campaign::where('is_active', true)
            ->withCount(['donations' => fn ($q) => $q->where('status', 'completed')])
            ->find($id, ['id', 'title', 'description', 'target_amount', 'collected_amount', 'type', 'image', 'created_at']);

        if (! $campaign) {
            return response()->json(['message' => 'Campaign not found'], 404);
        }

        return response()->json($campaign);
    }
}
