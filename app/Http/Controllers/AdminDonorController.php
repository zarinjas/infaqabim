<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminDonorController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $donors = Donation::select('name', 'email', 'phone')
            ->distinct()
            ->when($request->search, fn ($q, $v) => $q->whereAny(['name', 'email', 'phone'], 'like', "%{$v}%"))
            ->paginate(20);

        return response()->json($donors);
    }
}
