<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessForeCastStatus extends Model
{
    use HasFactory;
    protected $table = 'business_forecast_statuses';
    protected $fillable = ['status_name','bizz_forecast_id','active_status'];
}
