<?php

namespace App\Notifications\TestNotifications;

use App\Notifications\BaseNotification;
use Illuminate\Mail\Mailable;

class NotificationOnly extends BaseNotification
{
    public function getIconClass(): ?string
    {
        return 'fa fa-info';
    }

    public function getTitle(): string
    {
        return 'Test Notification';
    }

    public function getDescription(): string
    {
        return 'This is a test notification';
    }

    public function getMailable(): ?Mailable
    {
        return null;
    }
}
