<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ULBDetails extends Model
{
    use HasFactory;
    

    protected $fillable = [
        'area',
        'population2011',
        // 'presentpopulation',
        'wards',
        'households',
        'commercial',
        'ABbusstand',
        'CDbusstand',
        'market_morethan_oneacre',
        'market_lessthan_oneacre',
        'lengthofroad',
        'lengthofrouteroad',
        'lengthofotherroad',
        'lengthoflanes',
        'lengthofpucca',
        'lengthofcutcha',
        'parks',
        'parksforpublicuse',
        'tricycle',
        'bov',
        'bovrepair',
        'lcv',
        'lcvrepair',
        'compactor',
        'hookloaderwithcapacity',
        'compactorbin',
        'hookloader',
        'tractortipper',
        'lorries',
        'jcb',
        'bobcat',
        'sanitaryworkers_sanctioned',
        'sanitaryworkers_inservice',
        'sanitarysupervisor_sanctioned',
        'sanitarysupervisor_inservice',
        'permanentdrivers',
        'regulardrivers',
        'publicgathering',
        'secondarystorage',
        'transferstation',
        'households_animatorsurvey',
        'assessments_residential',
        'assessments_commercial',
    ];
}
