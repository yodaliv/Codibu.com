<?php

namespace App\Admin\Controllers;

use App\Models\Network;
use App\Models\Theme;
use App\Models\Demo;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class NetworkController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Network';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Network());

        $grid->column('id', __('Id'));
        $grid->column('url', __('Url'));
        $grid->column('theme.name', __('Theme'));
        $grid->column('demos', __('Demos'))->display(function () {
            $count = Demo::where('network_id' , $this->id)->count();
            return $count;
        });
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(Network::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('url', __('Url'));
        $show->field('theme_id', __('Theme id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Network());

        $form->url('url', __('Url'));
        $form->select('theme_id', __('Theme'))->options(Theme::all()->pluck('name','id'))->required();

        return $form;
    }
}
