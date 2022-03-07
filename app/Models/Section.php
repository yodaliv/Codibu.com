<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    //

    protected $casts = [
        'content' =>'json',
    ];

    public static function getByName( $name ) {
        return self::where('title', $name)->first();
    }

    public static function getByNames( $names ) {
        return self::whereIn('title', $names)->get()->keyBy('title');
    }


}
