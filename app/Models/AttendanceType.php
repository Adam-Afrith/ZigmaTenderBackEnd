<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceType extends Model
{
    use HasFactory;
    protected $fillable  = ['attendanceType','acitveStatus','icon_class','created_by','edited_by'];
}
