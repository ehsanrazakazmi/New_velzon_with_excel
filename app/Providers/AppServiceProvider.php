<?php

namespace App\Providers;

use App\Models\Subscription;
use App\Models\Laptop;
use App\Models\Product;
use App\Observers\LaptopObserver;
use App\Observers\ProductObserver;
use App\Observers\SubscriptionObserver;
use Laravel\Cashier\Cashier;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Event;
use Illuminate\Mail\Events\MessageSending;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        // Use our Subscription subclass so the plan() relation is available
        // without patching Cashier inside vendor/.
        Cashier::useSubscriptionModel(Subscription::class);

        // Admin activity notifications (topbar bell).
        Product::observe(ProductObserver::class);
        Laptop::observe(LaptopObserver::class);
        Subscription::observe(SubscriptionObserver::class);

        // Outside production, never deliver to the domains RFC 2606 reserves for
        // testing (.test/.example/.invalid/.localhost). Automated tests create
        // accounts on those domains; without this the mail leaves the building
        // and bounces back into a real inbox. Returning false cancels the send.
        Event::listen(MessageSending::class, function (MessageSending $event) {
            if (app()->environment('production')) {
                return true;
            }

            $recipients = array_keys((array) $event->message->getTo());

            foreach ($recipients as $address) {
                // Reserved by RFC 2606/6761: never routable, always bounces.
                if (preg_match('/(\.(test|example|invalid|localhost)|@(localhost|example\.(com|net|org)))$/i', $address)) {
                    return false;
                }
            }

            return true;
        });

    }
}
