<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Plugin extends Model
{
    protected $appends = ['s3_url'];

    /**
     * The themes that belong to the plugin.
     */
    public function themes()
    {
        return $this->belongsToMany('App\Models\Theme', 'theme_plugins');
    }

    public function versions()
    {
        return $this->hasMany('App\Models\PluginVersion');
    }

    public function latestVersion()
    {
        return $this->hasOne('App\Models\PluginVersion')->orderByDesc('created_at');
    }

    public function getS3UrlAttribute()
    {
        $path = "plugins/{$this->slug}/{$this->latestVersion->version}/{$this->slug}.zip";
        return Storage::disk('s3')->url($path);
    }
}
