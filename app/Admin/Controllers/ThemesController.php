<?php

namespace App\Admin\Controllers;

use App\Models\PageEditor;
use App\Models\Theme;
use App\Models\Plugin;
use App\Models\SiteType;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Storage;

class ThemesController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Theme';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Theme());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('slug', __('Slug'));
        $grid->column('pageEditor.name', __('Page Editor'));
        $grid->column('version', __('Version'))->display(function () {
            return $this->versions()->max('version');;
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
        $show = new Show(Theme::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('slug', __('Slug'));
        $show->field('pageEditor.name', __('Page Editor'));
        $show->field('version', __('Version'));

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

        $show->plugins('Plugins', function ($plugins) {
            $plugins->resource('/admin/plugins');
            $plugins->id();
            $plugins->name();
            $plugins->created_at();
            $plugins->updated_at();
        });

        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @todo extract from style.css file
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Theme());

        $form->text('name', __('Name'))->help('Name must match the name in style.css')->required();
        $form->text('developer', __('Developer'))->required();
        $form->text('developer_link', __('Developer Link'))->required();
        $form->text('description', __('Description'));
        $form->text('info', __('Info'));
        $form->hidden('slug', __('Slug'));
        $form->select('site_types_id', __('Theme Type'))->options(SiteType::all()->pluck('name','id'))->required();
        $form->select('page_editor_id', __('Page Editor'))->options(PageEditor::all()->pluck('name','id'))->required();

        $form->hasMany('versions', function (Form\NestedForm $form) {
            $form->text('version');
            $form->text('download_url');
            $form->datetime('created_at');
        });

        // $form->file('theme', __('Theme Zipfile'))->name(function ($file) {
        //     $filename = Theme::slugify(request()->input('name')) . '/' . request()->input('version');
        //     return 'themes/'.$filename.'.'.$file->guessExtension();
        // })->required();

        $form->saving(function (Form $form) {
            $form->slug = Theme::slugify($form->name);
        });

        $form->multipleSelect('plugins','Plugins')->options(Plugin::all()->pluck('name','id'));
        return $form;
    }
}
