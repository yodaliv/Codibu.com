<?php

namespace App\Console\Commands;

use App\Models\Network;
use App\Models\NetworkPlugin;
use App\Models\PluginVersion;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DownloadNetworkPlugins extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'network:download';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'download plugins for networks';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        set_time_limit(0);
        ini_set('memory_limit', '2048M');
        $networks = Network::with('plugins')->get();
        foreach ($networks as $index => $network) {
            $domain = parse_url($network->url)['host'];
            foreach ($network->plugins as $plugin) {
                $pluginVersion = PluginVersion::where('plugin_id',$plugin->id)->orderBy('version','desc')->first();
                if ($pluginVersion){

                    $path = "themePlugins/{$domain}/plugins/";
                    $folder = storage_path($path);
                    if (!is_dir($folder)) {
                        mkdir(storage_path($path), 0777, true);
                    }

                    $path .= "{$pluginVersion->plugin->slug}.zip";
                    $this->download($path, $pluginVersion->download_url);
                }
            }
        }
    }

    public function download($path, $url)
    {
        if (Cache::has('gpldl_cookies')) {
            $cookies = Cache::get('gpldl_cookies');
            Log::info('test Cache'. json_encode(Cache::get('gpldl_cookies')));
        } else {
            $response = Http::asForm()->post('https://gpldl.com/wp-login.php',
                [
                    'log' => 'info@toprankon.com',
                    'pwd' => 'yulaandjino8282@@gp'
                ]
            );
            $cookies = $response->cookies->toArray();
            if (count($cookies) == 5){
                Cache::put('gpldl_cookies',$cookies,86,400);
            }
        }

        if (count($cookies) == 5){

            Log::info('Download Network plugin');
            foreach ($cookies as $cookie) {
                $modifyCookies[$cookie['Name']] = $cookie['Value'];
            }
            $cookies = str_replace(['"','{',':',',','}'],['','','=',';',''], json_encode($modifyCookies));
            $agent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.183 Safari/537.36';
        } else {
            return 'unauthorised';
        }
        $client        = new Client(['cookies' => true]);

        $response      = $client->request('GET', $url, [
            'headers' => [
                'Cookie'     => $cookies,
                'User-Agent' => $agent
            ],
            'sink' => storage_path($path),
        ]);
        Log::info('Path :'.  $path);
        $contents = File::get(storage_path($path));

        Storage::disk('local')->put($path, $contents);

        Storage::disk('s3')->put($path, $contents, 'public');
        unlink(storage_path($path));
    }
}
