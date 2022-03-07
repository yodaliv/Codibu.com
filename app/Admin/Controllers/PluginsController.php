<?php

namespace App\Admin\Controllers;

use App\Models\Plugin;
use App\Models\Theme;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PluginsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Plugin';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Plugin());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('slug', __('Slug'));
        $grid->column('version', __('Version'))->display(function () {
            return $this->versions()->max('version');
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
        $show = new Show(Plugin::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('slug', __('Slug'));
        $show->versions('Versions', function ($versions) {

            $versions->resource('/admin/versions');
        
            $versions->id();
            $versions->version();
            $versions->download_url();
            $versions->created_at();
        
            $versions->filter(function ($filter) {
                $filter->like('version');
            });
        }); 
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
        $form = new Form(new Plugin());

        $form->text('name', __('Name'))->help('Name must match the name in plugin-name.php file')->required();
        $form->text('slug', __('Slug'))->help('Name must match the plugin\'s folder name or custom slug.')->required();

        $form->hasMany('versions', function (Form\NestedForm $form) {
            $form->text('version');
            $form->text('download_url');
            $form->datetime('created_at');
        });

        
        // if(!request()->input('name')) {
        //     $form->file('plugin', __('Plugin Zipfile'))->name(function ($file) {
        //         $filename = Theme::slugify(request()->input('name')) . '/' . request()->input('version') . '/' . request()->input('slug');
        //         return 'plugins/'.$filename.'.'.$file->guessExtension();
        //     })->required();
        // } else {
        //     $dir = 'files/plugins/' . Theme::slugify(request()->input('name')) . '/' . request()->input('version');
        //     $form->file('plugin', __('Plugin Zipfile'))->move($dir, request()->input('slug') . '.zip');
        // }

        return $form;
    }
}
