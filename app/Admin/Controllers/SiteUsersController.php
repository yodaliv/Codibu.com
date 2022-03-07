<?php

namespace App\Admin\Controllers;

use App\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use App\Admin\Actions\User\AssignUser;
use App\Admin\Actions\User\AssignUserCustomTool;
use App\Admin\Helper\Compositor;

use Encore\Admin\Facades\Admin;

class SiteUsersController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Site User';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */

    protected function grid()
    {
        $grid = new Grid(new User());
        if (Admin::user()->isRole('administrator')) {

            if (\Request::has('admin_user_id') && \Request::get('admin_user_id')
                && \Request::get('admin_user_id') != "_admin_user_id_") {
                $stuffId = \Request::get('admin_user_id');
                $userIds =
                    DB::table('admin_user_client_users')->where('admin_user_id', $stuffId)->get()->pluck('client_user_id');
                if (\Request::has('clients')) {
                    if (\Request::get('clients') == "unassigned") {
                        $grid->model()->whereNotIn('id', $userIds);
                    } else if (\Request::get('clients') == "assigned") {
                        $grid->model()->whereIn('id', $userIds);
                        $grid->disableRowSelector();
                    } else {
                        //
                    }
                }
            }
            $grid->disableCreateButton();
            $grid->tools(function ($batch) {
                $batch->append(new AssignUserCustomTool());
                $batch->append(new AssignUser());
            });
            $grid->actions(function ($actions) {
                $actions->disableDelete();
                $actions->disableEdit();
            });

        } else {
            $stuffId = Admin::user()->id;
            $userIds =
                DB::table('admin_user_client_users')->where('admin_user_id', $stuffId)->get()->pluck('client_user_id');
            $grid->model()->whereIn('id', $userIds);

            $grid->actions(function ($actions) {
                $actions->disableDelete();
                $actions->disableEdit();
            });
        }

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('email', __('Email'));
        $grid->column('role', __('Role'))->display(function () {
            $adminUser = DB::table('admin_users')->where('username', $this->email)->first();
            if (isset($adminUser->id)) {
                $userRole = DB::table('admin_role_users')->where('user_id', $adminUser->id)->first();
                if (isset($userRole->role_id)) {
                    $role = DB::table('admin_roles')->where('id', $userRole->role_id)->first();
                    return isset($role->id)
                        ? $role->name
                        : 'user';
                } else {
                    return 'user';
                }
            } else {
                return 'user';
            }
        });;
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
        $show = new Show(User::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('email', __('Email'));
        $show->field('role', __('Role'))->as(function () {
            $adminUser = DB::table('admin_users')->where('username', $this->email)->first();
            if (isset($adminUser->id)) {
                $userRole = DB::table('admin_role_users')->where('user_id', $adminUser->id)->first();
                if (isset($userRole->role_id)) {
                    $role = DB::table('admin_roles')->where('id', $userRole->role_id)->first();
                    return isset($role->id)
                        ? $role->name
                        : 'user';
                } else {
                    return 'user';
                }
            } else {
                return 'user';
            }
        });
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @param Request $request
     * @param $id
     * @return void
     */


    protected function form()
    {
        $form = new Form(new User());
        $form->text('name', __('Name'))->required();
        $form->text('email', __('Email'))->required();
        $form->select('admin_role_id', __('Role'))->options(DB::table('admin_roles')->get()->pluck('name', 'id'))->default(function ($form) {
            $adminUser = DB::table('admin_users')->where('username', $form->model()->email)->first();
            if (isset($adminUser->id)) {
                $userRole = DB::table('admin_role_users')->where('user_id', $adminUser->id)->first();
                return isset($userRole->role_id)
                    ? $userRole->role_id
                    : '';
            } else {
                return '';
            }
        })->required();

        $form->submitted(function (Form $form) {
            $form->ignore('admin_role_id');
        });

        $form->saving(function (Form $form) {
            $data = Request::all();

            DB::beginTransaction();

            $adminUserId = DB::table('admin_users')->insertGetId([
                'username' => $form->email,
                'name'     => $form->name,
                'password' => $form->model()->password
            ]);

            DB::table('admin_role_users')->insert([
                'user_id' => $adminUserId,
                'role_id' => $data['admin_role_id'],
            ]);

            DB::commit();
        });

        return $form;
    }

    public function updateSiteUserController(Request $request, $id)
    {
        dd($request->all());
    }
}
