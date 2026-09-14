<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

/**
 * A single admin-facing activity entry shown in the topbar bell dropdown.
 *
 * Stored on the `database` channel only - these are in-app notices, not email.
 */
class AdminActivity extends Notification
{
    public $title;
    public $message;
    public $icon;
    public $colour;
    public $url;
    public $actor;

    public function __construct($title, $message, $icon = 'ri-notification-3-line', $colour = 'bg-info-subtle', $url = null, $actor = null)
    {
        $this->title   = $title;
        $this->message = $message;
        $this->icon    = $icon;
        $this->colour  = $colour;
        $this->url     = $url;
        $this->actor   = $actor;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title'   => $this->title,
            'message' => $this->message,
            'icon'    => $this->icon,
            'colour'  => $this->colour,
            'url'     => $this->url,
            'actor'   => $this->actor,
        ];
    }
}
