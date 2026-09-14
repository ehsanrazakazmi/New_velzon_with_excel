<?php

namespace App\Observers;

use App\Models\Subscription;
use App\Support\AdminNotifier;

/**
 * Watches subscriptions for activation.
 *
 * Sitting on the model rather than the checkout controller means this fires for
 * every path that writes a subscription - the in-app Stripe checkout, plan
 * swaps, and Cashier's webhook handler (renewals, out-of-band activations,
 * reactivations) - since all of them go through Eloquent.
 */
class SubscriptionObserver
{
    private const ACTIVE = 'active';

    public function created(Subscription $subscription)
    {
        if ($subscription->stripe_status === self::ACTIVE) {
            $this->announce($subscription);
        }
    }

    public function updated(Subscription $subscription)
    {
        // Only when the status actually transitioned into "active", otherwise
        // routine writes (quantity, trial dates) would spam the bell.
        if ($subscription->wasChanged('stripe_status')
            && $subscription->stripe_status === self::ACTIVE) {
            $this->announce($subscription);
        }
    }

    private function announce(Subscription $subscription)
    {
        $who  = optional($subscription->user)->name ?? 'A user';
        $plan = optional($subscription->plan)->name;

        AdminNotifier::send(
            'Subscription activated',
            $plan
                ? sprintf('%s activated the %s plan.', $who, $plan)
                : sprintf('%s activated a subscription.', $who),
            'ri-bank-card-line',
            'bg-primary-subtle',
            route('subscriptions.all')
        );
    }
}
