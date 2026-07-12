<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminDonorController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $donors = Donation::select('donor_name', 'donor_email', 'donor_phone')
            ->distinct()
            ->when($request->search, fn ($q, $v) => $q->whereAny(['donor_name', 'donor_email', 'donor_phone'], 'like', "%{$v}%"))
            ->paginate(20);

        return response()->json($donors);
    }
}
