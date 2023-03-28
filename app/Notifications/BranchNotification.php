<?php

namespace App\Notifications;

use App\Services\Library\LibraryNotification;
use Carbon\Carbon;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class BranchNotification
{
    public static function send($notifiables, Notification $notification)
    {
        foreach($notifiables as $notifiable) {
            $uuid = Uuid::uuid1()->toString();
            DB::table('notifications')->insert([
                'id' => $uuid,
                'branch_code' => $notification->data['branch_code'] ?? null,
                'notifiable_type' => get_class($notifiable),
                'notifiable_id' => $notifiable->id,
                'type' => get_class($notification),
                'data' => json_encode($notification->getData()),
                'read_at' => null,
                'created_at' => Carbon::now()
            ]);
            if (get_class($notification) == \App\Notifications\Library\BookRequestAcceptNotification::class || get_class($notification) == \App\Notifications\Library\BookRequestNotification::class){
                LibraryNotification::create([
                    'notification_uuid' => $uuid,
                    'notifiable_type' => get_class($notifiable),
                    'notifiable_id' => $notifiable->id,
                    'book_request_id' => isset($notification->data['request_id']) ? $notification->data['request_id'] : null,
                ]);
            }
        }
    }
}
