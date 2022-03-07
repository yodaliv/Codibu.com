<?php

namespace App\Admin\Actions\User;

use Illuminate\Support\Facades\Request;
use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;
use DB;

class AssignUserCustomTool extends AbstractTool
{

    protected function script()
    {
        if ( \Request::has('admin_user_id') ) {
            $admin_user_id = \Request::get('admin_user_id');
            session()->put('admin_user_id', $admin_user_id);
        } else {
            session()->forget('admin_user_id');
            $admin_user_id = "";
        }
        $url = Request::fullUrlWithQuery(
            [
                'admin_user_id' =>  \Request::has('admin_user_id') ? \Request::get('admin_user_id') : '_admin_user_id_',
                'clients' => 'unassigned',
            ]
        );

        $replaceVal = $admin_user_id != "" ? $admin_user_id  : "_admin_user_id_";

        return <<<EOT
            $('select.admin_user_id').change(function () {
                var fromReplace = '='+"$replaceVal";
                var toReplace = '='+$(this).val();
                var url = "$url".replace(fromReplace, toReplace );
                $.pjax({container:'#pjax-container', url: url });
            });
            $('.assigned-users').click(function () {
                var url = "$url".replace('unassigned', "assigned");
                $.pjax({container:'#pjax-container', url: url });
            });
            $('.unassigned-users').click(function () {
                var url = "$url".replace('unassigned', "unassigned");
                $.pjax({container:'#pjax-container', url: url });
            });
            $('.reset').click(function () {
                var url = window.location.href.replace(window.location.search,'');
                $.pjax({container:'#pjax-container', url: url });
            });
        EOT;
    }

    public function render()
    {
        Admin::script($this->script());

        $adminRoleIds = DB::table('admin_roles')->where('slug', '!=', 'administrator')->get()->pluck('id');
        $adminUserIds = DB::table('admin_role_users')->whereIn('role_id', $adminRoleIds)->get()->pluck('user_id');
        $options =  DB::table('admin_users')->whereIn('id', $adminUserIds)->get()->pluck('name', 'id');

        return view('admin.tools.assignUser', compact('options'));
    }

}
