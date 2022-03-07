<?php

namespace App\Admin\Actions\SupportTickets;

use Encore\Admin\Admin;
use Encore\Admin\Actions\RowAction;
use Illuminate\Support\Facades\Request;
use App\Models\Ticket;
use DB;

class AddCommentCustomTool extends RowAction
{

    protected $selector = ".add-comment";

    public function __construct()
     {
    $this->selector = ".add-comment";
    }

    public function handle(Request $request)
    {
        // $request ...

        return $this->response()->success('Success message...')->refresh();
    }



    public function script()
    {

        return <<<SCRIPT
        $(document).ready(function(){
            $(document).on('submit','.add-comment',function (e) {

                var form_data = $(this).serialize();
                var form = $(this).serializeArray();
                var id = 0;
                $.each(form, function(i, field){
                    console.log(field);
                    if(field.name=="ticket_id"){
                        id = field.value;
                    }
                });
                console.log(form_data,id);

                if(id){
                    $('#loadingmessage').removeClass('hide');
                    console.log(id);
                    $.ajax({
                        method: 'POST',
                        url: 'admin-comment',
                        data: form_data,
                        dataType:'JSON',
                        success: function (data) {
                            console.log(data);
                            $('#loadingmessage').addClass('hide');
                            toastr.success(data.message);
                            $("#myModal"+id).modal('hide');
                        },
                        error:function(request){
                            //reject(request);
                            $('#loadingmessage').addClass('hide');
                            $("#myModal"+id).modal('hide');
                            toastr.error('Oops! Something went wrong!');
                        }
                    });
                }
                return false;
            });
        });
        SCRIPT;
    }

    public function html()
    {

       $id = $this->getKey();
       $ticket = Ticket::where('id',$this->getKey())->first();
       $comments = $ticket->comments;

       $html = '';
        if(!empty($comments)){
            foreach ($comments as $comment)
            {
                if($comment->is_admin==0){
                    $user_name = !empty($comment->user->name) ? $comment->user->name : '';
                }else{
                    $user = DB::table('admin_users')->where('id', $comment->user_id)->first();
                    $user_name = $user->username;
                }
                $label = $comment->is_admin ? "default" : "success";
                $html .= "<div class='panel panel-box panel-$label'>
                    <div class='panel panel-heading p-4'>".$user_name.
                    "<span class='pull-right'>".$comment->created_at->format('Y-m-d')." </span>
                </div><div class='panel panel-body'>".$comment->comment."</div></div>";
            }
        }

        $csrf=csrf_token();
        return <<<EOT
        <!-- Button triggers the modal box -->

<!-- Modal box (Modal) -->
<div class="modal fade" id="myModal{$id}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
   <div class="modal-header">
       <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">Comment Details</h4>
   </div>
   <form id="AddComment{$id}" method="post" class="add-comment" action="javascript:void(0);">
   <input type="hidden" name="ticket_id" value="{$ticket->id}">
   <input type="hidden" name="_token" value="$csrf">
   <div class="modal-body text-center" style="height:300px;overflow-y: auto;">
        <div class="ticket-info">
            <p> Message : $ticket->message </p>
            <p>Categry :  $ticket->category </p>
            <p>Status :  $ticket->status
            </p>
            <p>Created on:  $ticket->created_at </p>
        </div>
        <hr>

        <div class="comments">
            $html
        </div>
        <div class="row">
            <div class="col-md-12">
                <textarea rows="5" name="comment" class="form-control" placeholder="Add Comment" required></textarea>
            </div>
        </div>
   </div>
   <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-sm btn-primary" >Submit</button>
   </div>
   </form>
</div><!-- /.modal-content -->
</div><!-- /.modal -->
</div>
EOT;
    }

    public function render()
    {
        Admin::style('#loadingmessage{position: fixed;left: 0;top: 0;width: 100%;height: 100%;z-index: 999999999999;background: rgb(0 0 0 / 0.5);}
        #loadingmessage img{width:250px;position: fixed;top: 50%;left: 50%;transform: translate(-50%, -50%);background-color: transparent;}
        ');
        Admin::html($this->html());
        Admin::html('<div id="loadingmessage" class="loader hide"><img src='.url('assets/images/loader.gif').' class="user-image" alt="User Image"></div>');
        Admin::script($this->script());
    }

}
