<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SiteRenewalNotifyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $site;
    public $diff;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($site, $diff)
    {
        $this->site = $site;
        $this->diff = $diff;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Upcoming Site Renewal Notice")
            ->markdown('emails.sites.renewal-notify');
    }
}
