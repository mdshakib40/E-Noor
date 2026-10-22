<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\RiderLocation;

class OrderTrackingController extends Controller
{
    public function show(Order $order)
    {
        abort_unless(
            (int) $order->customer_id === (int) auth()->id(),
            403
        );

        $status = $order->status instanceof \BackedEnum
            ? $order->status->value
            : (string) $order->status;

        if (! in_array($status, [
            'picked_up',
            'out_for_delivery',
        ], true)) {
            return response()->json([
                'available' => false,
                'message' => 'Live tracking is not available yet.',
                'status' => $status,
            ]);
        }

        $location = RiderLocation::query()
            ->where('order_id', $order->getKey())
            ->where('rider_id', $order->rider_id)
            ->first();

        if (! $location) {
            return response()->json([
                'available' => false,
                'message' => 'Waiting for rider location.',
                'status' => $status,
            ]);
        }

        return response()->json([
            'available' => true,
            'status' => $status,
            'location' => [
                'latitude' => $location->latitude,
                'longitude' => $location->longitude,
                'heading' => $location->heading,
                'speed' => $location->speed,
                'recorded_at' => $location->recorded_at?->toISOString(),
            ],
        ]);
    }
}
