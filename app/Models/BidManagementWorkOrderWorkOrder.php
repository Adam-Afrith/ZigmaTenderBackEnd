<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidManagementWorkOrderWorkOrder extends Model
{
    use HasFactory;

    protected $fillable = ['bidid','orderquantity','priceperunit','loadate','orderdate','agreedate','sitehandoverdate','wofile','agfile','shofile','createdby_userid','updatedby_userids'];
}
