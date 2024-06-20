<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoneMaster extends Model
{
    use HasFactory;
    protected $fillable = ['country_id','zone_name','active_status','created_at','updated_at'];
    
}
