<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerCreationBankDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'ifsccode',
        'bankname',
        'bankaddress',
        'beneficiaryaccountname',
        'accountnumber',
        'cust_creation_mainid',
    ];
}
