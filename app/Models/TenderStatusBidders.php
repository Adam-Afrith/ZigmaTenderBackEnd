<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenderStatusBidders extends Model
{
    use HasFactory;
    protected $fillable = ['bidid','competitorId','acceptedStatus','reason','created_userid','updated_userid'];
}
