<?php

namespace App\Http\Controllers\Stripe;

use App\Models\Plan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {
        $plans = Plan::get();
   
        return view("stripe/plans", compact("plans"));
    }  
   
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function show(Plan $plan, Request $request)
    {
        $intent = auth()->user()->createSetupIntent();
   
        return view("stripe/subscription", compact("plan", "intent"));
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function subscription(Request $request)
    {
        $plan = Plan::find($request->plan);
        $subscription = $request->user()->newSubscription($request->plan, $plan->stripe_plan)
                        ->create($request->token);
        return redirect()->route('main-plans')->with('success','User created successfully');
    }   
}
