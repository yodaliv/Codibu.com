<?php

namespace App\Admin\Controllers;

use App\Models\Comment;
use App\Mail\CommentTicket;
use App\Models\Ticket;
use Encore\Admin\Controllers\AdminController;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mail;

class CommentsController extends AdminController
{
    /**
     * Persist comment and mail user
     * @param  Request  $request
     * @param  AppMailer $mailer
     * @return Response
     */
    public function postComment(Request $request)
    {
        // $this->validate($request, [
        //     'comment' => 'required',
        // ]);
        
        $comment = Comment::create([
            'ticket_id' => $request->input('ticket_id'),
            'user_id' => Auth::user()->id,
            'is_admin' => 1,
            'comment' => $request->input('comment'),
        ]);
        $ticket = Ticket::find($request->input('ticket_id'));
        //$comment = Comment::find(1);
        $user_name = Auth::user()->username;
        // send mail if the user commenting is not the ticket owner
        //if ($comment->ticket->user->id !== Auth::user()->id) {
            //$mailer->sendTicketComments($comment->ticket->user, Auth::user(), $comment->ticket, $comment);
            Mail::to($ticket->user->email)->send(new CommentTicket($comment->ticket->user, $user_name ,$comment->ticket, $comment));
        //}
            return json_encode(array('status' => true, 'message' => 'Your comment has been submitted.'));
        //return redirect()->back()->with("status", "Your comment has be submitted.");
    }
}
