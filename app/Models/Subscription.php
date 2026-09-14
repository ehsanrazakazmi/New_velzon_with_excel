<?php

namespace App\Models;

use Laravel\Cashier\Subscription as CashierSubscription;

class Subscription extends CashierSubscription
{
    /**
     * Cashier eager-loads items; we add the local plan alongside it.
     */
    protected $with = ['items', 'plan'];

    /**
     * The local plan this subscription maps to.
     *
     * Cashier stores the Stripe price identifier on the subscription; our
     * plans table keys the same value as `plan_id`.
     */
    public function plan()
    {
        return $this->hasOne(Plan::class, 'plan_id', 'stripe_price');
    }
}
