<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceEntry extends Model
{
    use HasFactory;
    protected $fillable  = ['userId','attendanceType','icon_class','created_by','edited_by'];
}
