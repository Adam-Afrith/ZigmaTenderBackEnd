<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidManagementWorkOrderLetterOfAcceptence extends Model
{
    use HasFactory;
    protected $fillable = ['bidid','date','refrence_no','from','medium','med_refrence_no','medium_select','wofile','createdby_userid','updatedby_userid'];
}
