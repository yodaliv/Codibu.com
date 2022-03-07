<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\SupportTickets\Replicate;
use App\Admin\Actions\SupportTickets\AddComment;
use App\Admin\Actions\SupportTickets\AddCommentCustomTool;
use App\Models\Ticket;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\User;
use Illuminate\Support\Str;

class SupportTicketsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Support Tickets';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Ticket());

        $grid->column('id', __('Id'));
        $grid->column('title', __('Title'));
        $grid->column('category', __('Category'));
        $grid->column('user.name', __('User Name'));

        $grid->column('priority', __('Priority'));
        $grid->column('status')->display(function ($status, $column) {
            if($status=='Open'){
                return $column->label();
            }else{
                return $column->label('danger');
            }
        });

        $grid->column('created_at', __('Created at'))->display(function ($time) {
            return date('d-m-Y H:i:s', strtotime($time));
        });
        $grid->column('updated_at', __('Updated at'))->display(function ($time) {
            return date('d-m-Y H:i:s', strtotime($time));
        });

        $grid->actions(function ($actions){
            if($actions->row->status=='Open'){
                $actions->add(new Replicate());
            }
            $actions->add(new AddComment);
            $actions->add(new AddCommentCustomTool);
            $actions->disableEdit();
        });

        $grid->disableCreateButton();


        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Ticket::findOrFail($id));

        $show->field('id', __('Id'));
        //$show->field('name', __('Name'));
        $show->field('title', __('Title'));
        $show->field('category', __('Category'));
        $show->field('user.name', __('User Name'));
        $show->field('status', __('Status'));
        $show->field('message', __('Message'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

}
