<?php

namespace App\Mail;

use App\Models\Site;
use App\Models\Ticket;
use Auth;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SupportTicketMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The site instance.
     *
     * @var Site
     */
    public $subject;
    public $view;
    public $message;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, Ticket $ticket,$notification=NULL)
    {
        // $this->to = $user->email;
      
        if($notification){
            $this->subject = "RE: $ticket->title (Ticket ID: $ticket->ticket_id)";
            $this->view = 'emails.ticket_info';
            $this->message = "<body>
            <p>Hello $user->name,</p><p>
                Your support ticket with ID #$ticket->ticket_id has been marked has resolved and closed.
            </p></body>";
        }else{
            $this->subject = "[Ticket ID: $ticket->ticket_id] $ticket->title";
            $this->view = 'emails.ticket_info';
            $this->message = "<body>
            <p>
                Thank you " . Auth::user()->name . " for contacting our support team. A support ticket has been opened for you. You will be notified when a response is made by email. The details of your ticket are shown below:
            </p><p>Title : " . $ticket->title . "</p><p>Priority : " . $ticket->priority . "</p><p>Status : " . $ticket->status . "</p>
            <p>You can view the ticket at any time at <a href=" . url('support-ticket/' . $ticket->id) . ">View Ticket Details</a></p></body>";
        }
    }

    /**
     * Send Ticket information to user
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown($this->view)->subject($this->subject)->with('email_content', $this->message);
    }
}
