<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallLog extends Model
{
    use HasFactory;
    protected $table = 'call_log_creations';
    protected $fillable = [
        
        'customer_id',
        'call_date',
        'call_type_id',
        'bizz_forecast_id',
        'bizz_forecast_status_id',
        'executive_id',
        'procurement_type_id',
        'action',
        'next_followup_date',
        'close_date',
        'close_status_id',
        'additional_info',
        'remarks',
        'created_by',
        // 'filename',
        // 'filetype'
    
    ];
}
