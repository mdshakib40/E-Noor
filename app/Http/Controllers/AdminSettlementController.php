<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\RestaurantSettlement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminSettlementController extends Controller
{
    public function markPaid(
        Request $request,
        RestaurantSettlement $settlement
    ): RedirectResponse {
        abort_unless(
            auth()->check()
            && auth()->user()->role === UserRole::SUPER_ADMIN,
            403
        );

        $updated = DB::transaction(function () use ($settlement) {
            $locked = RestaurantSettlement::query()
                ->whereKey($settlement->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($locked->status !== 'pending') {
                return false;
            }

            $locked->status = 'paid';
            $locked->settled_at = now();
            $locked->save();

            return true;
        });

        return back()->with(
            $updated ? 'success' : 'error',
            $updated
                ? 'Settlement marked as paid.'
                : 'This settlement is already paid.'
        );
    }
}