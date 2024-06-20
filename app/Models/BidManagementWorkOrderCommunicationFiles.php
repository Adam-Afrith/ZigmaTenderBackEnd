<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidManagementWorkOrderCommunicationFiles extends Model
{
    use HasFactory;
   
    protected $fillable = ['bidid','date','refrenceno','randomno','from','to','subject','medium','med_refrenceno','comfile','created_userid','updated_userid'];

}
