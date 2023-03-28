<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Libraries\SharedFunctions;

class ReadyForPickupMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $email;
    public $subject;
    public $pickup_date;

    public function __construct($data, $email, $subject, $pickup_date)
    {
        $this->data = $data;
        $this->email = $email;
        $this->subject = $subject;
        $this->pickup_date = $pickup_date;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.request.pickup'
        , [
            'data' => $this->data,
            'email' => $this->email,
            'date' =>  $this->pickup_date,
            'img' => SharedFunctions::get_school()['small_logo']
        ])
        ->subject($this->subject);
    }
}
