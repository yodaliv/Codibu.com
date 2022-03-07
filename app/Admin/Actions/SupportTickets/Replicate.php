<?php

namespace App\Admin\Actions\SupportTickets;

use App\Models\Ticket;
use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;
use App\Mail\SupportTicketMail;
use Mail;

class Replicate extends RowAction
{
    public $name = 'Close Ticket';

    public function handle(Model $model)
    {

        // $model ...
        $ticket = Ticket::where('id', $model->id)->firstOrFail();

        $ticket->status = 'Closed';

        $ticket->save();

        $ticketOwner = $ticket->user;

        Mail::to($ticketOwner->email)->send(new SupportTicketMail($ticketOwner, $ticket,'sendTicketStatusNotification'));

        return $this->response()->success('The ticket has been closed.')->refresh();
    }

    public function dialog()
    {
        $this->confirm('Are you sure to close this ticket?');
    }





}
