<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDonationRequest;
use App\Models\Donation;
use App\Models\Setting;
use App\Services\SenangPayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DonationController extends Controller
{
    /**
     * Public status lookup for the donor's return page - identified by the
     * opaque reference_number only, and returns only non-sensitive fields.
     */
    public function status(string $reference): JsonResponse
    {
        $donation = Donation::where('reference_number', $reference)->first();

        if (! $donation) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json([
            'status' => $donation->status,
            'amount' => (float) $donation->amount,
            'reference_number' => $donation->reference_number,
        ]);
    }

    public function index(): JsonResponse
    {
        $donations = Donation::with('campaign')
            ->latest()
            ->paginate(20)
            ->through(fn ($d) => [
                'id' => $d->id,
                'donor_name' => $d->donor_name,
                'donor_email' => $d->donor_email,
                'amount' => (float) $d->amount,
                'status' => $d->status,
                'payment_gateway' => $d->payment_gateway,
                'reference_number' => $d->reference_number,
                'created_at' => $d->created_at->toDateString(),
                'campaign' => $d->campaign?->title,
            ]);

        return response()->json($donations);
    }

    /**
     * Public endpoint - a donor submits a donation from the DonatePage.
     * Status is always forced to 'pending' server-side; a donor can never
     * set their own donation to completed/approved via this endpoint.
     */
    public function store(StoreDonationRequest $request, SenangPayService $senangPay): JsonResponse
    {
        $data = $request->validated();
        $paymentMethod = $data['payment_method'];

        $allowedMethod = Setting::where('key', 'payment_method')->value('value') ?? 'manual';
        if ($allowedMethod !== 'both' && $paymentMethod !== $allowedMethod) {
            return response()->json(['message' => 'Selected payment method is not available.'], 422);
        }

        $donation = new Donation([
            'campaign_id' => $data['campaign_id'],
            'donor_name' => $data['donor_name'],
            'donor_email' => $data['donor_email'],
            'donor_phone' => $data['donor_phone'],
            'amount' => $data['amount'],
            'status' => 'pending', // never trust client input for status
            'payment_gateway' => $paymentMethod,
        ]);

        if ($paymentMethod === 'manual') {
            if ($request->hasFile('receipt_image')) {
                $path = $request->file('receipt_image')->store('receipts', 'public');
                $donation->receipt_image = $path;
            }
            $donation->save();

            return response()->json([
                'message' => 'Donation submitted. Menunggu pengesahan admin.',
                'donation' => $donation,
            ], 201);
        }

        // SenangPay flow
        if (! $senangPay->isConfigured()) {
            return response()->json(['message' => 'SenangPay is not configured yet. Please contact the administrator.'], 422);
        }

        $donation->save();

        $orderId = 'DON-'.$donation->id.'-'.now()->timestamp;
        $donation->reference_number = $orderId;
        $donation->save();

        $paymentUrl = $senangPay->buildPaymentUrl(
            orderId: $orderId,
            amount: (float) $donation->amount,
            detail: 'Donation #'.$donation->id,
            name: $donation->donor_name,
            email: $donation->donor_email,
            phone: $donation->donor_phone,
        );

        return response()->json([
            'message' => 'Redirecting to SenangPay.',
            'donation' => $donation,
            'redirect_url' => $paymentUrl,
        ], 201);
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
        $donation = Donation::findOrFail($id);

        if ($donation->receipt_image) {
            Storage::disk('public')->delete($donation->receipt_image);
        }

        $donation->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
