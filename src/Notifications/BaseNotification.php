<?php

namespace Qopiku\Updater\Notifications;

use Illuminate\Notifications\Notification;

abstract class BaseNotification extends Notification
{
    /**
     * @return array<string>
     */
    public function via(): array
    {
        $notificationChannels = config('self-update.notifications.notifications.'.static::class);

        return array_filter($notificationChannels);
    }
}
