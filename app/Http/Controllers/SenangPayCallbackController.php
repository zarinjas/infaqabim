<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Services\SenangPayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Handles SenangPay's server-to-server callback (webhook) and the donor's
 * browser return redirect after completing/cancelling payment.
 *
 * Security notes:
 *  - The callback hash is verified with a timing-safe comparison before any
 *    donation status is trusted/updated (see SenangPayService::verifyCallbackHash).
 *  - The handler is idempotent: repeated callbacks for the same transaction
 *    will not double-increment campaign totals.
 */
class SenangPayCallbackController extends Controller
{
    /**
     * Server-to-server callback. SenangPay calls this directly - it must
     * respond quickly and must never trust the payload without verifying
     * the hash first.
     */
    public function callback(Request $request, SenangPayService $senangPay): JsonResponse
    {
        $statusId = (string) $request->input('status_id', '');
        $orderId = (string) $request->input('order_id', '');
        $transactionId = (string) $request->input('transaction_id', '');
        $msg = (string) $request->input('msg', '');
        $hash = (string) $request->input('hash', '');

        if (! $orderId || ! $hash) {
            Log::warning('SenangPay callback missing required fields', $request->all());
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        if (! $senangPay->verifyCallbackHash($statusId, $orderId, $transactionId, $msg, $hash)) {
            Log::warning('SenangPay callback hash verification failed', ['order_id' => $orderId]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $donation = Donation::where('reference_number', $orderId)->first();

        if (! $donation) {
            Log::warning('SenangPay callback for unknown order_id', ['order_id' => $orderId]);
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Idempotency: only transition + increment once.
        if ($donation->status === 'pending') {
            if ($statusId === '1') {
                $donation->update([
                    'status' => 'completed',
                    'gateway_transaction_id' => $transactionId,
                    'gateway_status_id' => $statusId,
                ]);
                $donation->campaign?->increment('collected_amount', $donation->amount);
            } else {
                $donation->update([
                    'status' => 'failed',
                    'gateway_transaction_id' => $transactionId,
                    'gateway_status_id' => $statusId,
                ]);
            }
        }

        return response()->json(['message' => 'OK']);
    }

    /**
     * Browser return URL - the donor lands here after SenangPay's hosted
     * payment page. This must NOT be trusted to update donation status
     * (a donor could revisit/replay this URL) - it only redirects to a
     * status page reflecting whatever the server-to-server callback already
     * recorded.
     */
    public function return(Request $request): RedirectResponse
    {
        $orderId = (string) $request->input('order_id', '');

        return redirect('/donate/status?order_id='.urlencode($orderId));
    }
}
