<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class communicationfilesmaster extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'refrence_no',
        'from_ulb',
        'from',
        'to_ulb',
        'to',
        'subject',
        'medium',
        'med_refrence_no',
        'toselect',
        'fromselect',
        'createdby_userid',
        'updatedby_userid',
    ];

}
