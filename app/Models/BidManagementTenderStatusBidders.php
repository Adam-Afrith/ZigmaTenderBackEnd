<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidManagementTenderStatusBidders extends Model
{
    use HasFactory;
    protected $fillable = ['bidid','no_of_bidders','tenderstatus','created_userid','updated_userid'];
}
