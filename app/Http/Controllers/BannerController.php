<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Banner::latest()->get());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'required|file|image|mimes:jpg,jpeg,png,webp|max:4096',
            'link' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $data['image'] = $request->file('image')->store('banners', 'public');

        $banner = Banner::create($data);

        return response()->json($banner, 201);
    }

    public function show($id): JsonResponse
    {
        return response()->json(Banner::findOrFail($id));
    }

    public function update(Request $request, $id): JsonResponse
    {
        $banner = Banner::findOrFail($id);

        $data = $request->validate([
            'title' => 'sometimes|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'sometimes|file|image|mimes:jpg,jpeg,png,webp|max:4096',
            'link' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($data);

        return response()->json($banner);
    }

    public function destroy($id): JsonResponse
    {
        $banner = Banner::findOrFail($id);

        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
