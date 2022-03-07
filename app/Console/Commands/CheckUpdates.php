<?php

namespace App\Console\Commands;

use App\Models\Theme;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckUpdates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'toprankon:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for theme & plugin updates';

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
        $links = [
            'https://gpldl.com/repository/premium-wordpress-themes/',
            'https://gpldl.com/repository/premium-wordpress-plugins/',
            'https://gpldl.com/repository/premium-woocommerce-extensions/',
            'https://gpldl.com/repository/special-gifts/',
        ];

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

            foreach ($links as $link) {
                $this->scrap($link, $cookies);
            }
        } else {
            return 'unauthorised';
        }

    }

    private function slug($download)
    {
        $slug = basename($download, '.zip');
        $slug = explode('-', $slug);
        $last = end($slug);
        if (is_numeric($last)) {
            array_pop($slug);
        }
        $slug = implode('-', $slug);
        $slug = explode('.', $slug);
        $last = end($slug);
        if (is_numeric($last)) {
            array_pop($slug);
        }
        $slug = implode('.', $slug);
        $slug = explode('_', $slug);
        $last = end($slug);
        if (is_numeric($last)) {
            array_pop($slug);
        }

        return implode('_', $slug);
    }

    private function scrap($url, String $cookies)
    {
        $agent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.183 Safari/537.36';

        $client        = new Client(['cookies' => true]);
        $response      = $client->request('GET', $url, [
            'headers' => [
                'Cookie'     => $cookies,
                'User-Agent' => $agent
            ],
        ]);

        $status = $response->getStatusCode();
        if ($status == 200) {
            $response = $response->getBody();
            $dom = new \DomDocument;
            libxml_use_internal_errors(true);
            $dom->loadHTML($response);
            libxml_clear_errors();

            $xpath = new \DOMXPath($dom);
            $entities = $xpath->query('//div[@class="gpldlitem sortitem"]');

            foreach ($entities as $entity) {
                $name = $xpath->query(".//span", $entity);
                $full_name = $name->item(0)->textContent;
                $object_type = (strpos($full_name, 'WordPress Theme') !== false || strpos($full_name, 'WordPress Child Theme') !== false || strpos($full_name, 'WooCommerce Theme') !== false) ? 'Theme' : 'Plugin';
                $object_class = "App\Models\\${object_type}";
                $name = str_replace([' WordPress Plugin', ' Addon', ' WordPress Theme'], '', $full_name);
                $name = ltrim(rtrim($name));
                $info = $xpath->query('.//div[@class="inf"]/span', $entity);
                $version = $info->item(0)->textContent;
                if (empty($version)) {
                    $version = 1;
                }
                $date = $info->item(1)->textContent;
                $info = $xpath->query('.//a', $info->item(2));
                $download = $info->item(0)->getAttribute("href");
                if (!empty($download[0]) && $download[0] == '/') {
                    $domain = explode("/", $url);
                    $download = !empty($domain[0]) && !empty($domain[1]) ? $domain[0] . "/" . $domain[1] . $download : $download;
                }
                $description = $xpath->query('.//div[@itemprop="description"]/text()', $entity);
                $description = strip_tags($description->item(0)->nodeValue);
                $developer = $xpath->query('.//span[@itemprop="manufacturer"]/a', $entity);
                if ($developer->length) {
                    $developer_name = $developer->item(0)->nodeValue;
                    $developer_link = $developer->item(0)->getAttribute("href");
                }
                $object_info = $xpath->query('.//div[2]/div[2]/a', $entity);
                if ($object_info->length) {
                    $object_info_link = $object_info->item(0)->getAttribute("href");
                }

                $slug = $this->slug($download) . '__' . Theme::slugify($developer_name);
                $object = $object_class::where('slug', $slug)->first();
                if (!$object) {
                    $object = new $object_class();
                }

                $object->name = $name;
                $object->description = $description;
                $object->developer = $developer_name;
                $object->developer_link = $developer_link;
                if ($object_info_link) {
                    $object->info = $object_info_link;
                }

                $object->slug = $slug;
                $object->save();
                if (!$object->versions()->where('version', $version)->count()) {
                    Log::info('version Chack and DB update');
                    $object_class .= "Version";
                    $typeVersion = new $object_class();
                    $typeVersion->{strtolower($object_type) . "_id"} = $object->id;
                    $typeVersion->version = $version;
                    $typeVersion->download_url = $download;
                    $typeVersion->save();
                }
            }

        } else {
            return ['status' => 'error', 'error' => 'Failed to scrap.'];
        }

    }

}
