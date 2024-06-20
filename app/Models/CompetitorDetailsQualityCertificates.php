<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitorDetailsQualityCertificates extends Model    
{
    use HasFactory;
    protected $fillable = ['compId','compNo','cerName','filepath','filetype','remark','cr_userid','edited_userid'];
}
