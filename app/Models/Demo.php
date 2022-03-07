<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Demo extends Model
{
    //

    protected $fillable = ['tags'];
    protected $appends  = ['theme_image'];

    public function network()
    {
        return $this->belongsTo(Network::class, 'network_id');
    }

    public function site_types()
    {
        return $this->belongsToMany(SiteType::class, 'site_type_demo');
    }

    public function getThemeImageAttribute(): string
    {
        $domainSlug = explode('.', $this->url);
        return 'https://toprankon.s3.amazonaws.com/demos/' . $this->network->theme->slug . '/' . $domainSlug[0] . '.png';
    }

    public function pageEditor()
    {
        return $this->belongsTO(pageEditor::class);
    }

}
