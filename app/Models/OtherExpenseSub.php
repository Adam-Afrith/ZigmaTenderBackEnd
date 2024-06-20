<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherExpenseSub extends Model
{
    use HasFactory;
    protected $table = 'other_expense_subs';
    protected $fillable = [
        'mainid',
        'action',
        'customer_id',
        'call_no',
        'expense_type_id',
        'amount',
        'description_sub',
        'filename',
        'originalfilename',
        'filetype',
        'filesize',
        'hasfilename',
        'created_by',
    ];

}
