<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UlbMaster extends Model
{
    use HasFactory;
    protected $fillable = ['ulb_name','ulb_status'];
}
