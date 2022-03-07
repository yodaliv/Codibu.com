<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanResource extends Model
{
    //
    protected $fillable = ['ParameterKey', 'ParameterValue', 'plan_id'];

    public $ParamKeys = [
        'InstanceType',
        'RDSInstanceType',
        'DBAllocatedStorage'
    ];

    
    public function paramInput( $key ) {
        switch($key){
            
        }
    }

}
