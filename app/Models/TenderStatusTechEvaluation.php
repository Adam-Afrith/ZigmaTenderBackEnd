<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenderStatusTechEvaluation extends Model
{
    use HasFactory;
    protected $fillable = ['bidid','competitorId','qualifiedStatus','reason','created_userid','edited_userid'];
}
