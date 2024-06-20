<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidManagementWorkOrderProjectDetails extends Model
{
    use HasFactory;
    protected $fillable = ['bidid','properiod','mobperiod','monsoonperiod','monthduration','supplyscape','supplydate','erectionstart','commercialproduc','tarcompletion','produccompletion','createdby_userid','updatedby_userid'];
}
