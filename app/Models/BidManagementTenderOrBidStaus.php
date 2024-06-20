<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidManagementTenderOrBidStaus extends Model
{
    use HasFactory;
    protected $fillable = [
        'status', 
        'file_original_name',
        'file_new_name',
        'file_type',
        'file_size',
        'ext',
        'edited_by'
    ];
}
