<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallLogFiles extends Model
{
    use HasFactory;
    protected $table = 'call_file_sub';
    protected $fillable = ['callid','mainid','originalfilename','filetype','filesize','hasfilename','createdby_userid','created_at','updated_at'];
    
}
