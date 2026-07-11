<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
            'image' => 'required|string',
            'link' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

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
            'image' => 'sometimes|string',
            'link' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $banner->update($data);

        return response()->json($banner);
    }

    public function destroy($id): JsonResponse
    {
        Banner::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
