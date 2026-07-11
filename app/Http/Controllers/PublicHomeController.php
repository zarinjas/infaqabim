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
            ->orderBy('collected_amount', 'desc')
            ->first(['id', 'title', 'description', 'target_amount', 'collected_amount', 'image']);

        $campaigns = Campaign::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get(['id', 'title', 'description', 'target_amount', 'collected_amount', 'image']);

        return response()->json([
            'banners' => $banners,
            'featured' => $featured,
            'campaigns' => $campaigns,
        ]);
    }
}
