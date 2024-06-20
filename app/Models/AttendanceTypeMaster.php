<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceTypeMaster extends Model
{
    use HasFactory;
    protected $fillable = [
        'attendance_type',
        'description',
        'status',
        'createdby',
        'updatedby',
        
    ];
}
