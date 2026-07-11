<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Campaign::latest()->get());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'target_amount' => 'required|numeric|min:0',
            'type' => 'required|in:progress,one_off',
            'is_active' => 'boolean',
            'image' => 'nullable|string',
        ]);

        $campaign = Campaign::create($data);

        return response()->json($campaign, 201);
    }

    public function show($id): JsonResponse
    {
        $campaign = Campaign::findOrFail($id);
        return response()->json($campaign);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $campaign = Campaign::findOrFail($id);

        $data = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'target_amount' => 'sometimes|numeric|min:0',
            'type' => 'sometimes|in:progress,one_off',
            'is_active' => 'boolean',
            'image' => 'nullable|string',
        ]);

        $campaign->update($data);

        return response()->json($campaign);
    }

    public function destroy($id): JsonResponse
    {
        $campaign = Campaign::findOrFail($id);
        $campaign->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
