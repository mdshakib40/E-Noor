<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\RestaurantSettlement;

class SslCommerzController extends Controller
{
    private function baseUrl(): string
    {
        return config('services.sslcommerz.sandbox', true)
            ? 'https://sandbox.sslcommerz.com'
            : 'https://securepay.sslcommerz.com';
    }

    public function initiate(Order $order)
    {
        abort_unless(auth()->id() === $order->customer_id, 403);

        if ($order->payment_method !== 'sslcommerz') {
            return back()->withErrors([
                'payment' => 'This order is not an online payment order.',
            ]);
        }

        if ($order->payment_status === 'paid') {
            return redirect()->route('dashboard');
        }

        $storeId = config('services.sslcommerz.store_id');
        $storePassword = config('services.sslcommerz.store_password');

        if (! $storeId || ! $storePassword) {
            return back()->withErrors([
                'payment' => 'Payment gateway credentials are not configured.',
            ]);
        }

        $transactionId = $order->transaction_id;

        if (! $transactionId) {
            $transactionId = 'NOOR' . $order->id . strtoupper(bin2hex(random_bytes(5)));
            $order->transaction_id = $transactionId;
            $order->payment_gateway = 'sslcommerz';
            $order->save();
        }

        try {
            $response = Http::asForm()
            ->withOptions([
                'verify' => 'C:/php85/extras/ssl/cacert.pem',
            ])
            ->timeout(30)
                ->post($this->baseUrl() . '/gwprocess/v4/api.php', [
                    'store_id' => $storeId,
                    'store_passwd' => $storePassword,
                    'total_amount' => number_format((float) $order->total, 2, '.', ''),
                    'currency' => 'BDT',
                    'tran_id' => $transactionId,
                    'product_category' => 'food',
                    'success_url' => route('sslcommerz.success'),
                    'fail_url' => route('sslcommerz.fail'),
                    'cancel_url' => route('sslcommerz.cancel'),
                    'ipn_url' => route('sslcommerz.ipn'),
                    'cus_name' => $order->customer_name,
                    'cus_email' => auth()->user()->email ?? 'customer@example.com',
                    'cus_phone' => $order->customer_phone,
                    'cus_add1' => $order->delivery_address,
                    'cus_city' => 'Pabna',
                    'cus_country' => 'Bangladesh',
                    'shipping_method' => 'NO',
                    'num_of_item' => $order->items()->sum('quantity'),
                    'product_name' => 'NOOR Food Order',
                    'product_profile' => 'general',
                ]);

            $data = $response->json();

            if (
                ! $response->successful() ||
                ($data['status'] ?? '') !== 'SUCCESS' ||
                empty($data['GatewayPageURL'])
            ) {
                Log::error('SSLCommerz session initiation failed', [
                    'order_id' => $order->id,
                    'response' => $data,
                ]);

                return back()->withErrors([
                    'payment' => 'Could not start online payment. Please try again.',
                ]);
            }

            return redirect()->away($data['GatewayPageURL']);
        } catch (\Throwable $e) {
            Log::error('SSLCommerz initiation error', [
                'order_id' => $order->id,
                'message' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'payment' => 'Payment gateway is temporarily unavailable.',
            ]);
        }
    }

    public function success(Request $request)
    {
        return $this->verifyPayment($request);
    }

    public function ipn(Request $request)
    {
        return $this->verifyPayment($request, true);
    }

   
private function verifyPayment(Request $request, bool $isIpn = false)
{
    $transactionId = $request->input('tran_id');
    $validationId = $request->input('val_id');

    if (! $transactionId || ! $validationId) {
        return response('Missing transaction information', 400);
    }

    $order = Order::where('transaction_id', $transactionId)->first();

    if (! $order || $order->payment_gateway !== 'sslcommerz') {
        return response('Order not found', 404);
    }

    if ($order->payment_status === 'paid') {
        return $isIpn
            ? response('OK', 200)
            : redirect()->route('dashboard')
                ->with('success', 'Payment already confirmed.');
    }

    try {
        $response = Http::withOptions([
            'verify' => 'C:/php85/extras/ssl/cacert.pem',
        ])->timeout(30)->get(
            $this->baseUrl() . '/validator/api/validationserverAPI.php',
            [
                'val_id' => $validationId,
                'store_id' => config('services.sslcommerz.store_id'),
                'store_passwd' => config('services.sslcommerz.store_password'),
                'format' => 'json',
            ]
        );

        $data = $response->json();

        $valid = $response->successful()
            && in_array($data['status'] ?? '', ['VALID', 'VALIDATED'], true)
            && ($data['tran_id'] ?? '') === $order->transaction_id
            && strtoupper($data['currency'] ?? '') === 'BDT'
            && abs((float) ($data['amount'] ?? 0) - (float) $order->total) < 0.01
            && (string) ($data['risk_level'] ?? '0') === '0';

        if (! $valid) {
            Log::warning('SSLCommerz validation rejected', [
                'order_id' => $order->id,
                'response' => $data,
            ]);

            return $isIpn
                ? response('Payment validation failed', 400)
                : redirect()->route('dashboard')
                    ->with('error', 'Payment could not be verified.');
        }

        DB::transaction(function () use ($order, $data, $validationId) {
            $lockedOrder = Order::whereKey($order->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedOrder->payment_status === 'paid') {
                return;
            }

            $lockedOrder->payment_status = 'paid';
            $lockedOrder->status = 'confirmed';
            $lockedOrder->validation_id = $validationId;
            $lockedOrder->bank_transaction_id = $data['bank_tran_id'] ?? null;
            $lockedOrder->save();

            // Demo commission: 10% of subtotal after discount.
            // Delivery fee is excluded from restaurant commission.
            $commissionBase = max(
                0,
                (float) $lockedOrder->subtotal - (float) $lockedOrder->discount
            );

            $commissionRate = 10.00;
            $commissionAmount = round(
                $commissionBase * $commissionRate / 100,
                2
            );

            $restaurantAmount = round(
                $commissionBase - $commissionAmount,
                2
            );

            RestaurantSettlement::firstOrCreate(
                ['order_id' => $lockedOrder->id],
                [
                    'restaurant_id' => $lockedOrder->restaurant_id,
                    'order_amount' => $commissionBase,
                    'commission_rate' => $commissionRate,
                    'commission_amount' => $commissionAmount,
                    'restaurant_amount' => $restaurantAmount,
                    'status' => 'pending',
                ]
            );

            \App\Models\CartItem::where(
                'user_id',
                $lockedOrder->customer_id
            )->delete();
        });

        return $isIpn
            ? response('OK', 200)
            : redirect()->route('dashboard')
                ->with('success', 'Payment successful!');

    } catch (\Throwable $e) {
        Log::error('SSLCommerz validation error', [
            'order_id' => $order->id,
            'message' => $e->getMessage(),
        ]);

        return $isIpn
            ? response('Validation service unavailable', 503)
            : redirect()->route('dashboard')
                ->with('error', 'Payment verification is pending.');
    }
}


    public function fail(Request $request)
    {
        return $this->markUnpaid($request, 'Payment failed.');
    }

    public function cancel(Request $request)
    {
        return $this->markUnpaid($request, 'Payment was cancelled.');
    }

    private function markUnpaid(Request $request, string $message)
    {
        $transactionId = $request->input('tran_id');

        if ($transactionId) {
            Order::where('transaction_id', $transactionId)
                ->where('payment_status', '!=', 'paid')
                ->update(['payment_status' => 'failed']);
        }

        return redirect()->route('dashboard')->with('error', $message);
    }
}
