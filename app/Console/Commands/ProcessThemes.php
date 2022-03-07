<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PluginVersion;
use App\Models\ThemeVersion;
use App\Models\Theme;
use App\Models\Plugin;

class ProcessThemes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'toprankon:update_info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update info from zips to database';

    private $slug = null;
    private $themes_bath = "/var/www/html/Toprankon/storage/app/files/themes";
    private $themes_tmp_bath = "/var/www/html/tmp_finished";
    
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
        $themes = Theme::all();
        // $files = glob("{$this->themes_bath}/*.zip", GLOB_BRACE);
        // $go = false;
        foreach($themes as $theme) {
            $version = $theme->versions()->orderBy('version', 'desc')->first();
            $themename = $theme->slug . '__' . $version->version . ".zip";
            $zip = "{$this->themes_bath}/{$themename}";
            // $themenewname = $theme->slug . '-' . Theme::slugify($theme->developer) . '-' .  $version->version .  ".zip";
            // if($theme->slug == 'SimplePressThemeRes') {
            //     $go = true;
            //     // continue;
            // }
            // if(!$go) continue;
            $this->processZip($theme, $zip, $themename);
        }
    }


    function deleteDirectory($dir) {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }

        }

        return rmdir($dir);
    }

    function get_string_between($string, $start = "", $end = ""){
        if (strpos($string, $start)) { // required if $start not exist in $string
            $startCharCount = strpos($string, $start) + strlen($start);
            $firstSubStr = substr($string, $startCharCount, strlen($string));
            $endCharCount = strpos($firstSubStr, $end);
            if ($endCharCount == 0) {
                $endCharCount = strlen($firstSubStr);
            }
            return substr($firstSubStr, 0, $endCharCount);
        } else {
            return '';
        }
    }

    function processZip($theme, $themeZip, $themename, $is_sub = false) {
        $version = $theme->versions()->orderBy('version', 'desc')->first();
        mkdir("{$this->themes_tmp_bath}/{$theme->slug}__{$version->version}", 0755);
        $command = "unar {$themeZip} -o {$this->themes_tmp_bath}/{$theme->slug}__{$version->version}"; 
        $output = [];
        exec($command, $output);
        if(!isset($output[1])) {
            echo "Failed@1 to extract {$themename}\n";
            return;
        }
        if($is_sub) {
            echo "Deleted: {$is_sub} \n";
            $this->deleteDirectory($is_sub);
        }

        $output = end($output);
        $destination = $this->get_string_between($output, "tmp_finished/", '"');
        if(empty($destination)) {
            return;
        }

        $destination = $this->themes_tmp_bath . '/' . $destination;

        if(!file_exists($destination."/style.css")) {
            echo "Failed@2 to extract {$themename} | Checking zip .\n";
            exec("mv {$destination} {$destination}.'_tmp'");
            // rename($destination, );
            $destination .= '_tmp';
            $files = glob("{$destination}/*.zip", GLOB_BRACE);
            $subFiles = array_filter($files, function($value){
                return strpos(strtolower($value), "document") === false && strpos(strtolower($value), "config") === false && strpos(strtolower($value), "demo") === false;
            });
            if(!count($subFiles)) {
                $sub_dirs = array_filter(glob($destination .'/*'), 'is_dir');
                $sub_dirs = array_filter($sub_dirs, function($value){
                    return strpos(strtolower($value), "document") === false && strpos(strtolower($value), "config") === false && strpos(strtolower($value), "demo") === false;
                });
                if(count($sub_dirs) == 1) {
                    $sub_dirs = array_values($sub_dirs);
                    $newThemeDir = explode('/', $sub_dirs[0]);
                    $newThemeDir = end($newThemeDir);
                    $destination = $this->themes_tmp_bath."/{$theme->slug}__{$version->version}/".$newThemeDir;          
                    rename($sub_dirs[0], $destination);
                    $this->deleteDirectory($sub_dirs[0]);
                } else {
                    echo "Failed@5 to extract {$destination}\n";
                    $theme->delete();
                    return;
                }
            } else {
                $subFiles = array_values($subFiles);
                if(count($subFiles)) {
                    return $this->processZip($theme, $subFiles[0], $themename, $destination);
                }
            }
        } 
        
        if(is_dir($destination) && file_exists($destination."/style.css")) {
            $dbName = "";
            $content = file_get_contents($destination."/style.css");
            preg_match('/(?s)^(?=.*?\bTheme[ ]+Name:[ ]*(?<theme_name>(?&info)))?(?=.*?\bTheme[ ]+URI:[ ]*(?<theme_uri>(?&info)))?(?=.*?\bDescription:[ ]*(?<desc>(?&info)))?(?=.*?\bAuthor:[ ]*(?<author>(?&info)))?(?=.*?\bAuthor[ ]+URI:[ ]*(?<author_uri>(?&info)))?(?=.*?\bVersion:[ ]*(?<version>(?&info)))?(?=.*?\bLicense:[ ]*(?<license>(?&info)))?(?=.*?\bLicense[ ]+URI:[ ]*(?<license_uri>(?&info)))?(?=.*?\bTags:[ ]*(?<tags>(?&info)))?(?(1)|(?(2)|(?(3)|(?(4)|(?(5)|(?(6)|(?(7)|(?(8)|(?(9)|(?!))))))))))(?(DEFINE)(?<info>(?-s:(?![ ]*\b(?:Theme[ ]+Name:|Theme[ ]+URI:|Description:|Author:|Author[ ]+URI:|Version:|License:|License[ ]+URI:|Tags:)).)*))/', $content, $themeinfo);

            if(isset($themeinfo['theme_name'])) {
                $dbName = Theme::slugify(rtrim(ltrim($themeinfo['theme_name'])));
                // $newname = $this->themes_tmp_bath . '/' .$dbName;
                // if($newname != $destination) {
                //     if(is_dir($newname)) {
                //         $rand = rand(9,99);
                //         $newname .= '_' . $rand;
                //         $dbName .=  '_' . $rand;
                //         echo("Zip for " . $this->slug ." already processed\n");
                //         exec("mv {$destination} {$newname}");
                //     }
                // }
            }

            if(!empty($dbName)) {
                if($theme) {
                    $theme->name = $themeinfo['theme_name'];
                    $theme->description = isset($themeinfo['desc']) ? $themeinfo['desc'] : null;
                    $file = $this->themes_bath . '/' . $theme->slug . '__' . $version->version . ".zip";
                    $old_slug = explode('__', $theme->slug);
                    $theme->slug = str_replace($old_slug[0], $dbName, $theme->slug);
                    $new_file = $this->themes_bath . '/' . $theme->slug . '__' . $version->version . ".zip";
                    rename($file, $new_file);
                    $theme->folder_uri = $dbName;
                    $theme->save();
                } else {
                    echo("Failed @3 To save " . $this->slug ."\n");
                }

                
            } else {
                echo "Failed@3 to extract {$this->slug}\n";
            }

        }
    }
}
