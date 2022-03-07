<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SiteType;
use App\Models\Plugin;
use App\Models\Demo;
use Illuminate\Support\Facades\Auth;
class PluginController extends Controller
{
    public function ajax(Request $request) {
        $keyword = $request->input('keyword');

        $plugins = Plugin::with('versions')->whereHas('versions', function($query) {
            $query->limit(1);
        });

        if($keyword && !empty($keyword)) {
            $plugins->where(function($query) use ($keyword){
                $query->where('name', 'like', '%' . $keyword . '%');
                $query->orWhere('developer', 'like', '%' . $keyword . '%');
                $query->orWhere('description', 'REGEXP', $keyword);
            });
        }


        $plugins = $plugins->paginate(15);
        $pagination = (string) $plugins->links();

        return json_encode(compact('plugins', 'pagination'));
    }

}
