<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use App\Models\Network;
use App\Models\Demo;
use App\Models\Theme;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Image;

class ImportDemos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'toprankon:sql_demos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download SQL demos';

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
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        set_time_limit(0);
        ini_set('memory_limit', '2048M');

        // $files = Storage::disk('public')->size('/demos/brooklyn/bathroomstore.png');
        // echo($files. "\n");//17502
        // $this->fixMedia();
        $this->compress();
        return;
        $networks = Network::with('theme')->get();

        foreach($networks as $network) {
            $this->import($network);
        }
    }

    public function compress() {

        $files = Storage::disk('public')->allFiles('/demos');
        foreach($files as $file) {
            if(strpos($file, ".DS_Store") !== false) continue; 

            $image = Image::make(storage_path('app/public/') . $file);
            $width = 345;
            $image->resize($width, null, function($constraint) {
                $constraint->aspectRatio();
            })->save(storage_path('app/public/') . $file);
        }

    }

    public function fixMedia() {
        $files = Storage::disk('public')->allFiles('/demos');
        foreach($files as $file) {
            $size = filesize(storage_path('app/public/') . $file);
            if($size == 11060) {
                $filename = explode('/', $file);
                $filename = explode('.', end($filename));
                $demo = Demo::with('network')->where('slug',  $filename[0])->first();
                if($demo) {
                    try {
                        $exec = "node /Users/home/Desktop/Clinets/topRankon/screenshot/screenshot.js {$demo->slug} {$demo->network->theme->slug} http://{$demo->url}";
                        $escaped_command = escapeshellcmd($exec);
                        exec($escaped_command);
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                    $contents = file_get_contents("/Users/home/Desktop/Clinets/topRankon/screenshot/screenshots/{$demo->network->theme->slug}/{$demo->slug}.png");
                    Storage::disk('public')->put("demos/{$demo->network->theme->slug}/{$demo->slug}.png", $contents);
                    echo "Updated {$demo->network->theme->slug}/{$demo->slug}.png\n";
                }
            }
    
        }

    }

    public function import( $network ) {
        $url =  "{$network->url}/exporter/multisite.php?password=6hAKqdqW2ksMSZ7x&action=list";

        $client = new Client();
        $response = $client->request('GET', $url);
        $status = $response->getStatusCode();
        if( $status == 200) {
            $sites = json_decode($response->getBody()->getContents());

            foreach($sites as $site) {
                if(strpos($site->name, '.') !== false ) {
                    $site->name = explode('.', $site->name);
                    $site->name = $site->name[0];
                }

                $exist = Demo::where('url', $site->domain)->count();
                if(!$exist) {
                    $demo = new Demo;
                    $demo->name = $site->name;
                    $demo->url = $site->domain;
                    $demo->network_id = $network->id;
                    $demo->site_types_id = 1; // default site type
                    $demo->blog_prefix = $site->blog_id; // default site type
                    $slug = explode('.', $site->domain);
                    $demo->slug = $slug[0];
                    $demo->save();
                    $theme_slug = $network->theme->slug;
                    try {
                        $exec = "node /Users/home/Desktop/Clinets/topRankon/screenshot/screenshot.js {$slug[0]} {$theme_slug} http://{$site->domain}";
                        $escaped_command = escapeshellcmd($exec);
                        exec($escaped_command);
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                    $contents = file_get_contents("/Users/home/Desktop/Clinets/topRankon/screenshot/screenshots/{$theme_slug}/{$slug[0]}.png");
                    Storage::disk('public')->put("demos/{$theme_slug}/{$slug[0]}.png", $contents);

                } else {
                    Demo::where('url', $site->domain)->update(['blog_prefix' => $site->blog_id]);
                }
            }
        }

    }
}
