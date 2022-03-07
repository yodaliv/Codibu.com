<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Plan extends Model
{
    protected $fillable = [
        'paypal_plan_id', 'stripe_plan_id', 'amazon_plan_id', 'price_after_discount'
    ];

    public function bundle()
    {
        return $this->belongsTo(Bundle::class, 'bundle_id');
    }

    public function specs()
    {
        return $this->hasMany(PlanSpecValue::class, 'plan_id');
    }

    public function resources()
    {
        return $this->hasMany(PlanResource::class, 'plan_id');
    }
}
