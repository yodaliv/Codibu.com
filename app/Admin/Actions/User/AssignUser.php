<?php

namespace App\Admin\Actions\User;

use Encore\Admin\Actions\BatchAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use DB;

class AssignUser extends BatchAction
{
    public $name = "<a class='assign-users btn btn-sm btn-info'><i class='fa fa-info-circle'></i>Assign</a>";
    protected $selector = '.assign-users';

    public function handle(Collection $collection, Request $request)
    {
        if ($request->session()->has('admin_user_id')) {
            $data = [];
            foreach ($collection as $model) {
                $data[] = [
                    'admin_user_id'  => intval(session()->get('admin_user_id')),
                    'client_user_id' => $model->id,
                ];
            }
            DB::table('admin_user_client_users')->insert($data);
        } else {
            return $this->response()->error('Please select moderator')->refresh();
        }
        return $this->response()->success('User assigned!')->refresh();
    }

/*    public function form()
    {
        $adminRoleIds = DB::table('admin_roles')->where('slug', '!=', 'administrator')->get()->pluck('id');
        $adminUserIds = DB::table('admin_role_users')->whereIn('role_id', $adminRoleIds)->get()->pluck('user_id');
        $this->select('admin_user_id', "Assign To" )->options(
            DB::table('admin_users')->whereIn('id', $adminUserIds)->get()->pluck('name', 'id')
        )->default( \Request::get('admin_user_id')?? '' )->required();
    }

    public function html()
    {
        return "<a class='assign-users btn btn-sm btn-info'><i class='fa fa-info-circle'></i>Assign</a>";
    }*/

}
