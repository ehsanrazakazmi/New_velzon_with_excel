<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Laptop;
use App\Models\Product;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Headline counts for the dashboard tiles.
     *
     * Each tile is gated on the same permission that guards the module it links
     * to, so a Mini-Admin is not told how many users exist on a screen they
     * cannot open. A tile the caller may not see is omitted entirely rather
     * than sent as zero - zero is a fact, absence is not.
     */
    public function stats(Request $request)
    {
        $user  = $request->user();
        $stats = [];

        if ($user->can('User list')) {
            $stats['users'] = [
                'total'   => User::count(),
                'pending' => User::whereNotNull('welcome_token')
                    ->orWhere('must_change_password', true)->count(),
            ];
        }

        if ($user->can('Product list')) {
            $stats['products'] = ['total' => Product::count()];
        }

        if ($user->can('Laptop list')) {
            $stats['laptops'] = [
                'total'     => Laptop::count(),
                'by_status' => Laptop::query()
                    ->selectRaw('status, count(*) as total')
                    ->groupBy('status')
                    ->pluck('total', 'status'),
            ];
        }

        $stats['subscriptions'] = [
            'active' => Subscription::where('stripe_status', 'active')->count(),
            'mine'   => $user->subscribed() ? 'active' : 'none',
        ];

        return response()->json(['data' => $stats]);
    }
}
