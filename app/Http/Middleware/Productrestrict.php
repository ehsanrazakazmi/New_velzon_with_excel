<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Plan;
use App\Models\Product;
use Illuminate\Http\Request;
use Laravel\Cashier\Subscription;

class Productrestrict
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        $subscription = Subscription::where('user_id', auth()->id())->where('stripe_status', 'active')->first();

        if (!$subscription) {
            // Handle the case where the user doesn't have an active subscription
        }

        $productLimit = 0;
        if ($subscription->plan->name == 'Basic') {
            $productLimit = 3;
        }
        elseif ($subscription->plan->name == 'professional') {
            $productLimit = 5;
        }
        elseif ($subscription->plan->name == 'enterprise') {
            $productLimit = 7;
        }

        $productCount = Product::where('user_id', $user->id)->count();
        if ($productCount >= $productLimit)
        {
            return redirect()->route('plans.all.update')->with('warning', 'You have reached your product limit for your current plan.');
        }

        return $next($request);
    }
}
