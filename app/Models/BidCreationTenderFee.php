<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidCreationTenderFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'bankname',
        'bankbranch',
        'mode',
        'dateofsubmission',
        'bgno',
        'ddno',
        'utrno',
        'dateofissue',
        'expiaryDate',
        'refno',
        'dateofpayment',
        'value',
        'file_original_name',
        'file_new_name',
        'file_type',
        'file_size',
        'ext',
        'updatedby_userid'
    ];


}
