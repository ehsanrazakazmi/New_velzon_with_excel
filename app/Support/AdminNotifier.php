<?php

namespace App\Support;

use App\Models\User;
use App\Notifications\AdminActivity;
use Illuminate\Support\Facades\Notification;

/**
 * Fans an activity notice out to every administrator.
 *
 * "Administrator" means a user holding one of self::ROLES.
 *
 * NOTIFY_ACTOR controls whether the person who caused the event also gets the
 * notice. It is true so the bell still works on a single-admin install - flip
 * it to false once there is more than one administrator and you would rather
 * not be told about your own actions.
 */
class AdminNotifier
{
    public const ROLES = ['Super Admin', 'Mini-Admin'];

    /** Also notify the user who triggered the event. */
    public const NOTIFY_ACTOR = true;

    public static function send($title, $message, $icon = 'ri-notification-3-line', $colour = 'bg-info-subtle', $url = null)
    {
        $actorId   = auth()->id();
        $actorName = auth()->check() ? auth()->user()->name : null;

        $recipients = User::role(self::ROLES)
            ->when($actorId && !self::NOTIFY_ACTOR, function ($query) use ($actorId) {
                return $query->where('id', '!=', $actorId);
            })
            ->get();

        if ($recipients->isEmpty()) {
            return;
        }

        Notification::send(
            $recipients,
            new AdminActivity($title, $message, $icon, $colour, $url, $actorName)
        );
    }
}
