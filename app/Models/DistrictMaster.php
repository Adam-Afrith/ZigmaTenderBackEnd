<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistrictMaster extends Model
{
    use HasFactory;
    protected $fillable = ['district_name','district_status','country_id','state_id'];
}
