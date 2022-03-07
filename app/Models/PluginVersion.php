<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Plugin;
use Illuminate\Support\Facades\Storage;

class PluginVersion extends Model
{
    //

    public function plugin()
    {
        return $this->belongsTo(Plugin::class);
    }

    public function s3_url() {
        $path = "plugins/{$this->plugin->slug}/{$this->version}/{$this->plugin->slug}.zip";
        return Storage::disk('s3')->url($path);
    }

}
