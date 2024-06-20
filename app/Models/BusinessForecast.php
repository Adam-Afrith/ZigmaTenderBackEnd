<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessForecast extends Model
{
    use HasFactory;
    protected $table = 'business_forecasts';
    protected $fillable = ['call_type_id','name','activeStatus','created_by'];
}
