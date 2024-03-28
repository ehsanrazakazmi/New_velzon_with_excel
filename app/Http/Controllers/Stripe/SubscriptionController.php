<?php

namespace App\Http\Controllers\Stripe;

use Exception;
use Stripe\Plan;
use App\Models\User;
use App\Services\savePlan;
use Illuminate\Http\Request;
use Laravel\Cashier\Cashier;
use App\Models\Plan as ModelPlan;
use Laravel\Cashier\Subscription;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class SubscriptionController extends Controller
{
    protected $stripeService;

    public function __construct(savePlan $stripeService)
    {
        $this->stripeService = $stripeService;
    }
    public function showPlanForm()
    {
        return view('stripe.plans.create');
    }
    public function savePlan(Request $request)
    {
        return $this->stripeService->savePlan($request);
    }

    public function allPlans()
    {
        $basic = ModelPlan::where('name', 'basic')->first();
        $professional = ModelPlan::where('name', 'professional')->first();
        $enterprise = ModelPlan::where('name', 'enterprise')->first();
        $hafta = ModelPlan::where('billing_method', 'week')->first();
        $mahina = ModelPlan::where('billing_method', 'month')->first();
        $saal = ModelPlan::where('billing_method', 'year')->first();
        $user = auth()->user();
        $intent = $user->createSetupIntent();
        return view('stripe.subscribe.plans', compact('basic', 'professional', 'enterprise', 'hafta', 'mahina', 'saal', 'intent'));
    }

    public function checkout(Request $request)
    {
        $user = Auth::user();
        // Check if the user is already subscribed
        if ($user->subscribed()) {
            // return 'false';
            return redirect()->back()->with('error', 'You are already subscribed');
        }

        // $planId = $request->plan_id;
        $planId = $request->planId;
        $plan = ModelPlan::where('plan_id', $planId)->first();
        if (!$plan) {
            // return 'false';
            return redirect()->back()->with('error', 'You does not have any plan to subscribe');

        }

        return response()->json([
            'plan' => $plan,
            'intent' => $user->createSetupIntent(),
        ]);
    }

    public function processPlan(Request $request)
    {

        $user = auth()->user();
        $user->createOrGetStripeCustomer();
        // $paymentMethod = null;
        $paymentMethod = $request->payment_method;

        if ($paymentMethod != null) {
            $paymentMethod = $user->addPaymentMethod($paymentMethod);
        }
        $plan = $request->plan_id;

        try {
            $user->newSubscription(
                'default', // Use the plan name as the subscription name
                $plan
            )->create($paymentMethod != null ? $paymentMethod->id : '');
        } catch (Exception $ex) {
            return back()->withErrors([
                'error' => 'Unable to create subscription due to this issue ' . $ex->getMessage()
            ]);
        }
        $request->session()->flash('alert-success', 'You are subscribed to this plan');
        return redirect()->route('plans.all', ['plan' => $plan]);
    }

    public function allSubscriptions()
    {
        $user = auth()->user();
        $invoices = $user->invoices();
        $subscriptions = Subscription::where('user_id', auth()->id())->get();
        return view('stripe.subscriptions.index', compact('subscriptions', 'invoices'));
    }
    public function cancelSubscriptions(Request $request)
    {
        $subscriptionName = $request->subscriptionName;
        if ($subscriptionName) {
            $user = auth()->user();
            $user->subscription($subscriptionName)->cancel();
            return 'subsc is canceled';
        }
    }
    public function resumeSubscriptions(Request $request)
    {
        $subscriptionName = $request->subscriptionName;
        if ($subscriptionName) {
            $user = auth()->user();
            $user->subscription($subscriptionName)->resume();
            return 'subsc is resumed';
        }
    }

    public function updateplans()
    {
        $basic = ModelPlan::where('name', 'basic')->first();
        $professional = ModelPlan::where('name', 'professional')->first();
        $enterprise = ModelPlan::where('name', 'enterprise')->first();
        $hafta = ModelPlan::where('billing_method', 'week')->first();
        $mahina = ModelPlan::where('billing_method', 'month')->first();
        $saal = ModelPlan::where('billing_method', 'year')->first();
        return view('stripe.update-plans', compact('basic', 'professional', 'enterprise', 'hafta', 'mahina', 'saal'));
    }

    public function updateSubscription($subscriptionName)
    {
        $user = auth()->user();
        $subscription = $user->subscription('default');
        if (!$subscription) {
            return back()->withErrors([
                'message' => 'Unable to locate the subscription.'
            ]);
        }
        // $plans = ModelPlan::all(); // Adjust this based on your plan retrieval logic
        $plan = ModelPlan::where('plan_id', $subscriptionName)->first();
        return view('stripe.plans.update', [
            'subscription' => $subscription,
            'plan' => $plan,
            'intent' => $user->createSetupIntent(),

        ]);
    }

    public function processUpdate(Request $request)
    {
        $user = auth()->user();
        $user->createOrGetStripeCustomer();
        // $paymentMethod = null;
        $paymentMethod = $request->payment_method;

        if ($paymentMethod != null) {
            $paymentMethod = $user->addPaymentMethod($paymentMethod);
        }
        $plan = $request->plan_id;

        try {
            $user->subscription('default')->swap($plan);
        } catch (Exception $ex) {
            return back()->withErrors([
                'error' => 'Unable to create subscription due to this issue ' . $ex->getMessage()
            ]);
        }
        $request->session()->flash('alert-success', 'You are subscribed to this plan');
        return redirect()->route('plans.all', ['plan' => $plan]);
    }

    public function invoice()
    {
        $user = auth()->user();
        $invoices = $user->invoices();
        return view('stripe.invoices', compact('invoices'));
    }
}
