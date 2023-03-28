<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Libraries\SharedFunctions;

class IDRequestApproveMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $email;
    public $subject;

    public function __construct($data, $email, $subject)
    {
        $this->data = $data;
        $this->email = $email;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.id_approval.approve'
        , [
            'data' => $this->data,
            'email' => $this->email,
            'img' => SharedFunctions::get_school()['small_logo']
        ])
        ->subject($this->subject);
    }
}
