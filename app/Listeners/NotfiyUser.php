<?php

namespace App\Listeners;

use Mail;
use App\Models\User;
use App\Mail\UserMail;
use App\Events\ProductCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotfiyUser
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ProductCreated  $event
     * @return void
     */
    public function handle(ProductCreated $event)
    {
        $users = User::get();
        foreach($users as $user){
            \Mail::to($user->email)->send(new UserMail($event->post));
        }
    }
}
