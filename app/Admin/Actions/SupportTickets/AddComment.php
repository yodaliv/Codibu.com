<?php

namespace App\Admin\Actions\SupportTickets;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ticket;
//use Encore\Admin\Admin;
use Illuminate\Support\Facades\Request;

class AddComment extends RowAction
{
   //public $name = '<a class="add_comment">Add Comment</a>';
   
    public function handle(Model $model)
    {
        // $model ...

        return $this->response()->success('Success message.')->refresh();
    }


    /**
     * @return mixed
     */
    public function render()
    {
        return "<a href='javascript:void(0);' class='comments add_comment' data-key=".$this->getKey()." data-toggle='modal' id='myModalbutton".$this->getKey()."' data-target='#myModal".$this->getKey()."'>Add Comment</a>";
            // $this->script();
            // return '<a href="javascript:void(0);" class="add_comment">Add Comment</a>';
        
    }

}