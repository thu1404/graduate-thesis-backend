<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Notify extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $date;
    public $adrress;
    public function __construct($date, $adrress)
    {
        $this->date = $date;
        $this->adrress = $adrress;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('[Notification]')
            ->view('email', ['date' => $this->date, 'message' => $this->adrress]);
    }
}
