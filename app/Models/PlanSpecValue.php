<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Plan;
use App\Models\PlanSpec;

class PlanSpecValue extends Model
{
    //
    protected $fillable = ['plan_spec_id', 'plan_id', 'value'];

    
    public function plan()
    {
        return $this->belongsTo(Plan::class,'plan_id');
    }

    public function spec()
    {
        return $this->belongsTo(PlanSpec::class,'plan_spec_id');
    }
}