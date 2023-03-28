<?php

namespace App\Services\Library;

use Illuminate\Database\Eloquent\Model;

class LibraryNotification extends Model
{
    public $timestamps = false;
    protected $fillable = ['notification_uuid', 'notifiable_type', 'notifiable_id', 'book_request_id', 'request_status'];
}
