<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallAssignBDM extends Model
{
    use HasFactory;
    protected $table = 'bdm_has_customers';
    protected $fillable = [
        'bdm_id',
        'customer_id',
        'assign_status',
        'created_userid',
    ];
}
