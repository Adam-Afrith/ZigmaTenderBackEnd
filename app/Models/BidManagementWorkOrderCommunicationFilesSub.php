<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidManagementWorkOrderCommunicationFilesSub extends Model
{
    use HasFactory;
    protected $table = 'communication_files_subs';
    protected $fillable = ['randomno', 'mainid', 'comfile', 'filetype', 'createdby_userid', 'updatedby_userid'];
}