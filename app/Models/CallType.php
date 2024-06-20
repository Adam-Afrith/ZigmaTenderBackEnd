<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallType extends Model
{
    use HasFactory;
    protected $table = 'call_types_mst';
    protected $fillable = ['name','activeStatus','created_by'];
}
