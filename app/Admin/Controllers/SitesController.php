<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Site\BatchTerminate;
use App\Admin\Actions\Site\Payment\PartialRefund;
use App\Admin\Actions\Site\Payment\Refund;
use App\Admin\Actions\Site\Terminate;
use App\Models\Site;
use App\Models\Theme;
use App\Models\Plugin;
use Carbon\Carbon;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SitesController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Site';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Site());

        $grid->column('id', __('Id'));
        $grid->column('title', __('Title'));
        $grid->column('slug', __('Slug'));
        $grid->column('domain', __('Domain'));
        $grid->column('theme.name', __('Theme'));
        $grid->column('email', __('Email'))->default('-');
        $grid->column('status')->using(['deleted' => 'Terminated', 'completed' => 'Active','building'=>'Building'])->badge( 'green');
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        // Delete Terminate
        $grid->actions(function ($actions) {
            $actions->add(new Terminate());
            $actions->disableEdit();
        });
        // Delete Batch Terminate.
        $grid->batchActions(function ($batch) {
            $batch->add(new BatchTerminate());
        });

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
        $show = new Show(Site::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('slug', __('Slug'));
        $show->field('domain', __('Domain'));
        $show->field('email', __('Email'));
        $show->field('status')->using(['deleted' => 'Termineted', 'completed' => 'Active']);
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->user('User Details', function ($user){
            $user->field('id', __('Id'));
            $user->field('name', __('Name'));
            $user->field('email', __('Email'));
            $user->field('downloads', __('Downloads'));
            $user->panel()
                ->tools(function ($tools) {
                    $tools->disableEdit();
                    $tools->disableList();
                    $tools->disableDelete();
                });
        });
        $show->plan('Plan Details', function ($plan){
            $plan->field('id', __('Id'));
            $plan->field('name', __('Name'));
            $plan->field('description', __('Description'));
            $plan->field('duration', __('Duration'));
            $plan->field('price', __('Price'));
            $plan->panel()
                ->tools(function ($tools) {
                    $tools->disableEdit();
                    $tools->disableList();
                    $tools->disableDelete();
                });
        });
        $show->paymentHistories('Payment History', function ($payment) {
            $payment->id();
            $payment->payment_platform();
            $payment->payment_amount('Paid Amount ($)');
            $payment->refund_amount('Refund Amount ($)');
            $payment->created_at('Paid On')->display(function ($createdAt){
                return Carbon::parse($createdAt)->format('m/d/Y h:i a');
            });

            $payment->disablePagination();

            $payment->disableCreateButton();

            $payment->disableFilter();

            $payment->disableRowSelector();

            $payment->disableColumnSelector();

            $payment->disableTools();

            $payment->disableExport();

            $payment->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableView();
                $actions->disableEdit();
                $actions->disableDelete();
                $actions->add(new Refund());
                $actions->add(new PartialRefund());
            });

        });
        $show->theme('Theme Details', function ($theme){
            $theme->field('id', __('Id'));
            $theme->field('name', __('Name'));
            $theme->field('slug', __('Slug'));
            $theme->field('pageEditor.name', __('Page Editor'));
            $theme->panel()
                ->tools(function ($tools) {
                    $tools->disableEdit();
                    $tools->disableList();
                    $tools->disableDelete();
                });
            $theme->versions('Theme Versions', function ($versions) {
                $versions->resource('/admin/versions');
                $versions->id();
                $versions->version();
                $versions->download_url();
                $versions->created_at()->display(function ($createdAt){
                    return Carbon::parse($createdAt)->format('m/d/Y h:i a');
                });
                $versions->filter(function ($filter) {
                    $filter->like('version');
                });
                $versions->panel()
                    ->tools(function ($tools) {
                        $tools->disableEdit();
                        $tools->disableList();
                        $tools->disableDelete();
                    });
            $versions->disableActions();

            $versions->disablePagination();

            $versions->disableCreateButton();

            $versions->disableFilter();

            $versions->disableRowSelector();

            $versions->disableColumnSelector();

            $versions->disableTools();

            $versions->disableExport();

            $versions->actions(function (Grid\Displayers\Actions $actions) {
                    $actions->disableView();
                    $actions->disableEdit();
                    $actions->disableDelete();
                });
            });
        });
        $show->panel()
            ->tools(function ($tools) {
                $tools->disableEdit();
                $tools->disableList();
                $tools->disableDelete();
            });
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Site());

        $form->text('title', __('Title'));
        $form->text('slug', __('Slug'));
        $form->url('domain', __('Domain'));
        $form->select('theme_id', 'Theme')->options(Theme::all()->pluck('name','id'));
        $form->email('email', __('Email'));
        $form->multipleSelect('plugins','Plugins')->options(Plugin::all()->pluck('name','id'))->help('Only the plugins that are installed on site and not in theme plugin list.');

        return $form;
    }
}
