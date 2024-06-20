<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunicationFiles extends Model
{
    use HasFactory;
    protected $fillable = ['bidid','Date','RefrenceNo','From','To','Subject','Medium','Filepath','Filetype','cr_userid','edited_userid'];

}
    