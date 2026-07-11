<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function index(): JsonResponse
    {
        $donations = Donation::with('campaign')
            ->latest()
            ->get()
            ->map(fn ($d) => [
                'id' => $d->id,
                'donor_name' => $d->donor_name,
                'donor_email' => $d->donor_email,
                'amount' => (float) $d->amount,
                'status' => $d->status,
                'reference_number' => $d->reference_number,
                'created_at' => $d->created_at->toDateString(),
                'campaign' => $d->campaign?->title,
            ]);

        return response()->json($donations);
    }

    public function show($id): JsonResponse
    {
        $donation = Donation::with('campaign')->findOrFail($id);
        return response()->json($donation);
    }

    public function approve($id): JsonResponse
    {
        $donation = Donation::findOrFail($id);
        $donation->update(['status' => 'completed']);

        $donation->campaign?->increment('collected_amount', $donation->amount);

        return response()->json($donation->load('campaign'));
    }

    public function reject($id): JsonResponse
    {
        $donation = Donation::findOrFail($id);
        $donation->update(['status' => 'failed']);

        return response()->json($donation->load('campaign'));
    }

    public function destroy($id): JsonResponse
    {
        Donation::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
