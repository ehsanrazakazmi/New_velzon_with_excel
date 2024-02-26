<?php

namespace App\Services;

use Stripe\Plan;
use App\Models\Plan as ModelPlan;
use Exception;

class savePlan
{
    public function savePlan($request)
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        $amount = ($request->amount * 100);
        try {
            $plan = Plan::create([
                'amount' => $amount,
                'currency' => $request->currency,
                'interval' => $request->billing_period,
                'interval_count' => $request->interval_count,
                'product' => [
                    'name' => $request->name
                ]
            ]);

            ModelPlan::create([
                'plan_id' => $plan->id,
                'name' => $request->name,
                'price' => $plan->amount,
                'billing_method' => $plan->interval,
                'currency' => $plan->currency,
                'interval_count' => $plan->interval_count,
            ]);
        } catch (Exception $ex) {
            dd($ex->getMessage());
        }
        return redirect()->route('plans.create');
    }

    // Other methods...
}
