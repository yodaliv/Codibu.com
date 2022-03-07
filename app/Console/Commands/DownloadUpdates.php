<?php

namespace App\Console\Commands;

use App\Models\PluginVersion;
use App\Models\Theme;
use App\Models\ThemeVersion;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use \Illuminate\Support\Facades\File;

class DownloadUpdates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'toprankon:download';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download theme & plugin updates';

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
     * @return mixed
     */
    public function handle()
    {
        set_time_limit(0);
        ini_set('memory_limit', '2048M');
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
            foreach ($cookies as $cookie) {
                $modifyCookies[$cookie['Name']] = $cookie['Value'];
            }
            $cookies = str_replace(['"','{',':',',','}'],['','','=',';',''], json_encode($modifyCookies));
        } else {
            return 'unauthorised';
        }

        $pluginVersions = PluginVersion::where('downloaded', 0)->get();
        foreach ($pluginVersions as $pluginVersion) {
            if ($pluginVersion->downloaded  == 0){
                $path = "plugins/{$pluginVersion->plugin->slug}/{$pluginVersion->version}";
                $folder = storage_path($path);
                if (!is_dir($folder)) {
                    mkdir(storage_path($path), 0777, true);
                }

                $path .= "/{$pluginVersion->plugin->slug}.zip";
                $this->download($path, $pluginVersion->download_url, $cookies);
                Log::info('plugin ddd');
                $pluginVersion->downloaded += 1;
                $pluginVersion->save();
            }

        }

        $themeVersions = ThemeVersion::where('downloaded', 0)->get();
        foreach ($themeVersions as $themeVersion) {
            if ($themeVersion->downloaded == 0){
                $path = "themes";
                $folder = storage_path($path);
                if (!is_dir($folder)) {
                    mkdir(storage_path($path), 0777, true);
                }

                $developer = Theme::slugify($themeVersion->theme->developer);
                $path .= "/{$themeVersion->theme->slug}__{$themeVersion->version}.zip";
                $this->download($path, $themeVersion->download_url, $cookies);
                $themeVersion->downloaded += 1;
                $themeVersion->save();
            }
        }

    }

    public function download($path, $url, String $cookies)
    {
        $agent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.183 Safari/537.36';
        $client        = new Client(['cookies' => true]);
        $response      = $client->request('GET', $url, [
            'headers' => [
                'Cookie'     => $cookies,
                'User-Agent' => $agent
            ],
            'sink' => storage_path($path),
        ]);
        $contents = File::get(storage_path($path));

        Log::info('Download Updates');
        Storage::disk('s3')->put($path, $contents, 'public');
        unlink(storage_path($path));
    }
}
