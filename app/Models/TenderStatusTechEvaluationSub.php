<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenderStatusTechEvaluationSub extends Model
{
    use HasFactory;
    protected $table = "tender_status_tech_evaluations_subs";
    protected $fillable = ['techMainId', 'competitorId', 'qualifiedStatus', 'reason', 'created_userid', 'edited_userid'];
}
