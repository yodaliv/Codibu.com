<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Theme extends Model
{
    //

    protected $appends = ['s3_url'];

    /**
     * The plugins that belong to the theme.
     */
    public function plugins()
    {
        return $this->belongsToMany('App\Models\Plugin', 'theme_plugins');
    }

    public static function slugify($text, $replacor = '-')
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', $replacor, $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, $replacor);

        // remove duplicate -
        $text = preg_replace('~-+~', $replacor, $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    public static function clean($string)
    {
        $string = str_replace(' ', '-', $string);                // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

        return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
    }

    public function versions()
    {
        return $this->hasMany('App\Models\ThemeVersion');
    }

    public function pageEditor()
    {
        return $this->belongsTo(PageEditor::class);
    }

    public function latestVersion()
    {
        return $this->hasOne('App\Models\ThemeVersion')->orderByDesc('created_at');
    }

    public function getS3UrlAttribute()
    {
        $version = optional($this->latestVersion)->version;
        $path = "themes/{$this->slug}__{$version}.zip";
        return Storage::disk('s3')->url($path);
    }
}
