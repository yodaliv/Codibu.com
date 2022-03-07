<?php

namespace App\Models;

use Encore\Admin\Form\Field\HasMany;
use Illuminate\Database\Eloquent\Model;

class SiteType extends Model
{
    //
    protected $hidden     = ['pivot'];
    public    $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function demos()
    {
        return $this->belongsToMany(Demo::class, 'site_type_demo');
    }
}
