<?php

namespace Qopiku\Updater\Notifications\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Qopiku\Updater\Events\UpdateSucceeded as UpdateSucceededEvent;
use Qopiku\Updater\Notifications\BaseNotification;

final class UpdateSucceeded extends BaseNotification
{
    protected UpdateSucceededEvent $event;

    public function toMail(): MailMessage
    {
        return (new MailMessage())
            ->from(config('self-update.notifications.mail.from.address', config('mail.from.address')), config('self-update.notifications.mail.from.name', config('mail.from.name')))
            ->subject(config('app.name').': Update succeeded');
    }

    public function setEvent(UpdateSucceededEvent $event): self
    {
        $this->event = $event;

        return $this;
    }
}
