<?php
namespace App\Http\Controllers;

use App\Models\Network;
use App\Models\NetworkPlugin;
use App\Models\Plugin;
use App\Models\PluginVersion;
use App\Models\Site;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Aws\CloudFormation\CloudFormationClient;

/**
 * Class ApiController
 * @package App\Http\Controllers
 */
class ApiController extends Controller
{
    /**
     * @param Request $request
     * @return array|string[]
     */
    function getUpdates(Request $request) {
        $siteDomain = $request->input('domain');
        $siteDomain = rtrim($siteDomain,"/");
        $site = Site::where('domain', $siteDomain)->first();
        if(!$site) {
            return ['status' => 'error', 'error' => 'Site not found'];
        }

        $response = [];
        $response['plugins'] = $site->all_plugins();
        $themeVersion = $site->theme->versions()->orderBy('version', 'desc')->first();
        $response['theme'] = ['name' => $site->theme->name, 'version' => $themeVersion->version, 'file' => $themeVersion->s3_url()];
        $response['status']  = 'ok';
        return $response;
    }

    function download(){
        $network = Network::where('url', 'like', '%' . request('client_root') . '%')->with('plugins')->first();

        $plugins = [];
        if ($network && $network->plugins){
            foreach ($network->plugins as $index =>  $plugin) {
                $pluginB = Plugin::find($plugin->id);
                if($plugin->pivot->version <= $pluginB->latestVersion->version){
                    $networkPlugins = NetworkPlugin::where(['network_id'=>$network->id, 'plugin_id'=>$plugin->id])->first();
                    $networkPlugins->version = $pluginB->latestVersion->version;
                    $networkPlugins->save();

                    $plugins[$index]['name'] = $pluginB->name;
                    $plugins[$index]['version'] = $pluginB->latestVersion->version;
                    $plugins[$index]['download_url'] = "https://toprankon.s3.amazonaws.com/themePlugins/".request('client_root')."/plugins/".$pluginB->slug.".zip";//$pluginB->s3_url;
                }
            }
        }
        return response(json_encode($plugins),200);
    }
}
