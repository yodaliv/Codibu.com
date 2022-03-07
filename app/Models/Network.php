<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Network extends Model
{
    //



    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    /**
     * The plugins that belong to the network.
     */
    public function plugins()
    {
        return $this->belongsToMany('App\Models\Plugin', 'network_plugins')->withPivot('version');
    }

}
