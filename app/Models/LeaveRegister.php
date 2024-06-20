<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRegister extends Model
{
    use HasFactory;
    protected $table = 'leave_registers';
    protected $fillable = [
        'user_id',
        'attendance_type_id',
        'from_date',
        'to_date',
        'start_time',
        'reason',
        'latitude',
        'longitude',
        'created_by',
    ];
}
