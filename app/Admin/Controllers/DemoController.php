<?php

namespace App\Admin\Controllers;

use App\Models\Demo;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Models\SiteType;
use Illuminate\Support\Facades\DB;
use App\Admin\Actions\Demo\ImportTags;

class DemoController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Demo';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Demo());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('url', __('Url'))->display(function ($url) {
            return "<a href='http://{$url}' target='_blank'>http://{$url}</a>";
        });
        $grid->column('tags', __('Tags'));
        $grid->column('site_types', __('Category'))->display(function($values) {
            return \Arr::pluck($values, 'id', 'name');
        })->multipleSelect( SiteType::all()->pluck('name','id')->toArray() );
        $grid->column('created_at', __('Created at'));

        $grid->export(function ($export) {
            $export->filename('demos');
            $export->only(['id', 'name', 'tags']);
        });

        $grid->tools(function ($batch) {
            $batch->append(new ImportTags());
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
        $show = new Show(Demo::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('url', __('Url'));
        $show->field('theme_id', __('Theme id'));
        $show->field('site_types_id', __('Site types id'));
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
        $form = new Form(new Demo());

        $form->text('name', __('Name'));
        $form->text('url', __('Url'));
        $form->textarea('description', __('Description'));
        $form->textarea('tags', __('Tags'))->help("Separate between tags with a new line");


        $form->saving(function (Form $form) {
            $form->tags = str_replace([PHP_EOL, "\r\n", "\n\r", "\r", "\n"], ',', $form->tags);
            $form->tags = str_replace(',,', ',', $form->tags);
        });

        return $form;
    }
}
