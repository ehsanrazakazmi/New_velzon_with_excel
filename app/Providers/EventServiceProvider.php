<?php

namespace App\Providers;

use App\Events\ProductCreated;
use App\Listeners\NotfiyUser;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [      // here registered is an event!
            SendEmailVerificationNotification::class,   // here SendEmailVerificationNotification is a listener
        ],      // so the result would be like when ever it is registered , it will send notification

        ProductCreated::class => [
            NotfiyUser::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
