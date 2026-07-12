<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CampaignController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Campaign::latest()->paginate(20));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'target_amount' => 'required|numeric|min:0',
            'type' => 'required|in:progress,one_off',
            'is_active' => 'boolean',
            'image' => 'nullable|file|image|mimes:jpg,jpeg,png,webp|max:4096',
            'gallery' => 'nullable|array',
            'gallery.*' => 'file|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('campaigns', 'public');
        }

        $campaign = Campaign::create($data);

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $image) {
                $path = $image->store('campaigns/gallery', 'public');
                $campaign->galleries()->create(['image_path' => $path]);
            }
        }

        return response()->json($campaign->load('galleries'), 201);
    }

    public function show($id): JsonResponse
    {
        $campaign = Campaign::with('galleries')->findOrFail($id);
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
            'image' => 'nullable|file|image|mimes:jpg,jpeg,png,webp|max:4096',
            'gallery' => 'nullable|array',
            'gallery.*' => 'file|image|mimes:jpg,jpeg,png,webp|max:4096',
            'delete_gallery' => 'nullable|array',
            'delete_gallery.*' => 'integer|exists:campaign_galleries,id',
        ]);

        if ($request->hasFile('image')) {
            if ($campaign->image) {
                Storage::disk('public')->delete($campaign->image);
            }
            $data['image'] = $request->file('image')->store('campaigns', 'public');
        }

        $campaign->update($data);

        // Handle deleting gallery images
        if ($request->has('delete_gallery')) {
            $galleriesToDelete = $campaign->galleries()->whereIn('id', $request->delete_gallery)->get();
            foreach ($galleriesToDelete as $gallery) {
                if (Storage::disk('public')->exists($gallery->image_path)) {
                    Storage::disk('public')->delete($gallery->image_path);
                }
                $gallery->delete();
            }
        }

        // Handle adding new gallery images
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $image) {
                $path = $image->store('campaigns/gallery', 'public');
                $campaign->galleries()->create(['image_path' => $path]);
            }
        }

        return response()->json($campaign->load('galleries'));
    }

    public function destroy($id): JsonResponse
    {
        $campaign = Campaign::findOrFail($id);

        if ($campaign->image) {
            Storage::disk('public')->delete($campaign->image);
        }

        $campaign->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
