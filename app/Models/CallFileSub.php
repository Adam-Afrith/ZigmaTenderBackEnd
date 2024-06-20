<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallFileSub extends Model
{
    use HasFactory;
    protected $table = 'call_file_sub';
    protected $fillable = [
        'mainid',
        'filename',
        'originalfilename',
        'filetype',
        'filesize',
        'hasfilename',
        'createdby_userid',
    ];
}
