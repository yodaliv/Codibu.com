<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Site;

class UserWithsite extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The site instance.
     *
     * @var Site
     */
    public $site;

    public $is_new = false;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Site $site, $is_new = false)
    {
        $this->site = $site;
        $this->is_new = $is_new;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('no-reply@codibu.com', 'CODIBU')
                    ->subject('Your website is being created!')
                    ->view('emails.sites.userwithsite');
    }
}
