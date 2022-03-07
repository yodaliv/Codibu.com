<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SiteType;
use App\Models\Theme;
use App\Models\Demo;
use App\Models\PageEditor;
use Illuminate\Support\Facades\Auth;
class ThemeController extends Controller
{

    public function ajax(Request $request) {
      $site_type = $request->input('site_type');
      $keyword = $request->input('keyword');
      $theme_type = $request->input('theme_type');
      $demo_name = $request->input('demo_name');
      $editor_type = $request->input('etype');
        if($theme_type == 'plain') {

            if($site_type) {
                if(is_array($site_type)) {
                    $themes = Theme::whereIn('site_types_id', $site_type);
                } else {
                    $themes = Theme::where('site_types_id', (int) $site_type);
                }
                $themes = $themes->where('folder_uri', '!=', '');
            }  else {
                $themes = Theme::where('folder_uri', '!=', '');
            }

            if($demo_name){
              if(is_array($demo_name))
              {
                $themes = Demo::orWhereIn('id', $demo_name);
              }else{
                $themes = Demo::orWhere('id', (int) $demo_name);
              }
            }

            if($editor_type){
              if(is_array($editor_type))
              {
                  $themes->orWhere(function($query) use ($editor_type){
                    $query->WhereHas('pageEditor'  , function ( $query ) use ($editor_type){
                        $query->WhereIn('page_editors.id', $editor_type);
                    });
                  });
              }else{
                $themes->orWhere(function($query) use ($editor_type){
                  $query->orWhereHas('pageEditor'  , function ( $query ) use ($editor_type){
                    $query->orWhere('page_editors.id', (int) $editor_type);
                  });
                });
              }
            }

            $themes->with('versions','pageEditor')->whereHas('versions', function($query) {
                $query->limit(1);
            });

            if($keyword && !empty($keyword)) {
                $themes->where(function($query) use ($keyword){
                    $query->where('name', 'like', '%' . $keyword . '%');
                    $query->orWhere('developer', 'like', '%' . $keyword . '%');
                    $query->orWhere('description', 'REGEXP', $keyword);
                    $query->orWhereHas('pageEditor'  , function ( $query ) use ($keyword){
                        $query->where('name', 'like', '%' . $keyword . '%');
                    });
                });
            }

        } else {
            $themes = Demo::with('network')->with('network.theme')->with('network.theme.pageEditor');

            if(is_array($site_type) && count($site_type)) {
                $themes->whereHas('site_types', function($q) use($site_type){
                    $q->whereIn('site_type_id', $site_type);
                });
            }

            if($keyword && !empty($keyword)) {
                $themes->where(function($query) use ($keyword){
                    $query->orWhereHas('network'  , function ( $query ) use ($keyword){
                        $query->WhereHas('theme'  , function ( $query ) use ($keyword){
                            $query->where('name', 'like', '%' . $keyword . '%');
                            $query->orWhere('developer', 'like', '%' . $keyword . '%');
                            $query->orWhereHas('pageEditor'  , function ( $query ) use ($keyword){
                                $query->where('name', 'like', '%' . $keyword . '%');
                            });
                        });
                    });
                    $query->orWhere('name', 'like', '%' . $keyword . '%');
                    $query->orWhere('description', 'like', '%' . $keyword . '%');
                    $query->orWhere('tags', 'like', '%' . $keyword . '%');
                    $query->orWhereRaw('find_in_set("'.$keyword.'", tags)');
                });
            }

            if($demo_name){
              if(is_array($demo_name))
              {
                $themes->orWhereIn('id', $demo_name);
              }else{
                $themes->orWhere('id', (int) $demo_name);
              }
            }

            if($editor_type){
              if(is_array($editor_type))
              {
                    $themes->orWhere(function($query) use ($editor_type){
                              $query->WhereHas('network.theme.pageEditor'  , function ( $query ) use ($editor_type){
                                  $query->WhereIn('page_editors.id', $editor_type);
                              });
                    });
              }else{

                $themes->orWhere(function($query) use ($editor_type){
                  $query->WhereHas('network.theme.pageEditor'  , function ( $query ) use ($editor_type){
                    $query->Where('page_editors.id', (int) $editor_type);
                  });
                });
              }
            }
        }

        $themes = $themes->paginate(15);
        $pagination = (string) $themes->links();

        return json_encode(compact('themes', 'pagination'));
    }
}
