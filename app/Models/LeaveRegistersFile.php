<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRegistersFile extends Model
{
    use HasFactory;
    protected $table = 'leave_registers_files';
    protected $fillable = [
        'mainid',
        'filename',
        'filetype',
        'filesize',
        'hasfilename',
        'created_by',
    ];
}
