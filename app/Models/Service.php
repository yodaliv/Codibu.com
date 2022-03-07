<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    public function getImageAttribute($image)
    {
        return "https://".env("AWS_BUCKET").".s3.amazonaws.com/".$image;
    }
}
