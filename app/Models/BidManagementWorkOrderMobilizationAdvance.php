<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidManagementWorkOrderMobilizationAdvance extends Model
{
    use HasFactory;
    protected $fillable = ['bidid','mobadvance','bankname','bankbranch','mobadvMode','datemobadv','validupto','createdby_userid','updatedby_userid'];

}
