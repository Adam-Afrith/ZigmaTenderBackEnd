<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoneHasState extends Model
{
    use HasFactory;
    protected $fillable = ['zone_id','state_id','created_at','updated_at'];
}
