<?php

namespace App\Notifications\TestNotifications;

use App\Notifications\BaseNotification;
use Illuminate\Mail\Mailable;

class NotificationWithRoute extends BaseNotification
{
    public $data;

    /**
     * Creates a new notification instance
     *
     * @return void
     */
    public function __construct(String $branch_code, Int $id)
    {
        $this->data = ['branch_code' => $branch_code, 'id' => $id];
    }

    public function getIconClass(): ?string
    {
        return 'fa fa-info';
    }

    public function getTitle(): string
    {
        return 'Test Clickable Notification';
    }

    public function getDescription(): string
    {
        return 'This is a test clickable notification';
    }

    public function getActionRoute(): ?string
    {
        return '/employees/view/'.$this->data['branch_code'].'/'.$this->data['id'];
    }

    public function getActionParams(): ?array
    {
        return [
            'id' => $this->data['id']
        ];
    }

    public function getMailable(): ?Mailable
    {
        return null;
    }

    public function getData()
    {
        return parent::toArray();
    }
}
