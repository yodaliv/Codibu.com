<?php

namespace App\Mail;

use App\Models\Site;
use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CommentTicket extends Mailable
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
    public function __construct($ticketOwner, $user, Ticket $ticket, $comment)
    {
       
        // $this->to = $user->email;
        //$this->to = $ticketOwner->email;
        $user_name = !empty($user->name) ? $user->name : '';
        if(empty($user_name)){
            $user_name = $user;
        }
        $this->subject = "RE: $ticket->title (Ticket ID: $ticket->ticket_id)";
        $this->view = 'emails.ticket_comments';
        $this->message = "<body><p>". $comment->comment." </p>
	    <p>Replied by : ".  $user." </p>
        <p>Title : " . $ticket->title . "</p><p>Status: " . $ticket->status . "</p>
        <p>You can view the ticket at any time at <a href=" . url('support-ticket/' . $ticket->id) . ">View Ticket Details</a></p></body>";
        
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
