<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageEditor extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The themes that belong to the editor.
     */
    public function themes()
    {
        return $this->hasMany(Theme::class);
    }
}
