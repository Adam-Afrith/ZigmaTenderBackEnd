<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallHistory extends Model
{
    use HasFactory;
    protected $table = 'call_histories';
    protected $fillable = [
        

        'main_id',
        'customer_id',
        'call_date',
        'call_type_id',
        'bizz_forecast_id',
        'bizz_forecast_status_id',
        'additional_info',
        'executive_id',
        'procurement_type_id',
        'action',
        'next_followup_date',
        'description',
        'close_date',
        'close_status_id',
        'remarks',
        'created_by',
        
    ];
}
