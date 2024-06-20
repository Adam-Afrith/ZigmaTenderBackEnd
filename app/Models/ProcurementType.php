<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcurementType extends Model
{
    use HasFactory;
    protected $table = 'call_procurement_types';
    protected $fillable = ['name','active_status','created_userid','edited_userid'];
}
